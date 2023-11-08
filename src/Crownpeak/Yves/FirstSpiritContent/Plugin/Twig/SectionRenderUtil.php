<?php

namespace Crownpeak\Yves\FirstSpiritContent\Plugin\Twig;

use Spryker\Shared\Log\LoggerTrait;
use Twig\Environment;
use Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentFactory;
use Crownpeak\Yves\FirstSpiritContent\Exception\FirstSpiritContentTemplateException;

/**
 * Utility class to render sections.
 */
class SectionRenderUtil
{
    use LoggerTrait;

    protected Environment $twig;
    protected FirstSpiritContentFactory $factory;

    public function __construct(Environment $twig, FirstSpiritContentFactory $factory)
    {
        $this->twig = $twig;
        $this->factory = $factory;
    }

    /**
     * Renders rich texts recursivly using the mapped Twig template.
     * 
     * @param array $section The data of the section to render.
     * @return string The rendered section as HTML.
     */
    public function renderSection(array $section): string
    {
        $cacheKey = md5(json_encode($section));
        if ($this->getFactory()->getStorageClient()->hasRenderedTemplate($cacheKey)) {
            $cacheResult = $this->getFactory()->getStorageClient()->getRenderedTemplate($cacheKey);
            $this->getLogger()->debug('[SectionRenderUtil] Found in cache ' . $section['previewId'] . ' (cache key=' . $cacheKey . ')');
            return $cacheResult;
        } else {
            try {
                $fullTemplate = $this->getTemplateForSection($section);
                $splitTemplate = explode('/', $fullTemplate);
                if (count($splitTemplate) !== 2) {
                    throw new FirstSpiritContentTemplateException('Invalid template path ' . $fullTemplate);
                }
                $templateModule = $splitTemplate[0];
                $template = $splitTemplate[1];
                $this->getLogger()->debug('[SectionRenderUtil] Attempting to render section ' . $section['previewId'] . ' with template ' . $template);
                $renderedBlock = $this->twig->render('@CmsBlock/template/fs_content_block.twig', [
                    'fsData' => $section,
                    'template' => $template,
                    'templateModule' => $templateModule
                ]);
                $this->getFactory()->getStorageClient()->setRenderedTemplate($cacheKey, $renderedBlock);
                return $renderedBlock;
            } catch (\Throwable $th) {
                $this->getLogger()->error('[SectionRenderUtil] Error during rendering of section ' . $section['previewId']);
                $this->getLogger()->error(sprintf(
                    '[SectionRenderUtil] %s\n%s',
                    $th->getMessage(),
                    $th->getTraceAsString()
                ));


                if ($this->getConfig()->shouldDisplayBlockRenderErrors()) {
                    // If errors should be displayed, re-throw so error page with details is displayed
                    throw $th;
                }
                $isPreview = $this->getFactory()->getPreviewService()->isPreview();
                if ($isPreview) {
                    // In preview, render basic information
                    return $this->getErrorMessage($th);
                }

                return '';
            }
        }
    }

    /**
     * Decorates the section by wrapping it into a container with the preview ID set when in preview.
     *
     * @param string $content The sections content to wrap.
     * @param string $previewId The preview ID of the section.
     * @return string The decorated section as HTML.
     */
    public function decorateSection(string $content, string $previewId = ''): string
    {
        $isPreview = $this->getFactory()->getPreviewService()->isPreview();
        $decoratedContent = '<div';
        if ($isPreview && !empty($previewId)) {
            $decoratedContent .= ' data-preview-id="' . $previewId . '"';
        }
        return $decoratedContent . '>' . $content . '</div>';
    }

    /**
     * Decorates a slot by wrapping it into a container with the slot name set when in preview.
     *
     * @param string $content The slots content to wrap.
     * @param string $slotName The slot name.
     * @return string The decorated slot as HTML.
     */
    public function decorateSlot(string $content, string $slotName): string
    {
        $isPreview = $this->getFactory()->getPreviewService()->isPreview();
        if ($isPreview) {
            return '<div data-fcecom-slot-name="' . $slotName . '">' . $content . '</div>';
        }
        return $content;
    }

    /**
     * Constructs an error message to display from the given error.
     * 
     * @param \Throwable $th The exception that occured.
     * @return string The rendered error to display as HTML.
     */
    public function getErrorMessage(\Throwable $th): string
    {
        $isPreview = $this->getFactory()->getPreviewService()->isPreview();
        if (!$isPreview) {
            return '';
        }
        return '<div style="text-align: center"><h2>Error</h2>' . $th->getMessage() . '</div>';
    }

    private function getFactory()
    {
        return $this->factory;
    }

    private function getConfig()
    {
        return $this->getFactory()->getConfig();
    }



    /**
     * Maps the type of the given section to a template.
     * Uses configured mapping array.
     *
     * @param mixed $section The section as retrieved from the API.
     * @return string The name of the template to use for rendering.
     */
    private function getTemplateForSection($section): string
    {
        $mapping = $this->getConfig()->getSectionTemplateMapping();
        $sectionType = $section['sectionType'];
        $fallbackMappingKey = '*';
        if (array_key_exists($sectionType, $mapping)) {
            $templateName = $mapping[$sectionType];
            $this->getLogger()->debug('[SectionRenderUtil] Using ' . $templateName . ' for ' . $sectionType);
            return $templateName;
        } else if (array_key_exists($fallbackMappingKey, $mapping)) {
            $fallbackTemplateName = $mapping[$fallbackMappingKey];
            $this->getLogger()->debug('[SectionRenderUtil] Using fallback mapping for ' . $sectionType);
            return $fallbackTemplateName;
        } else {
            $this->getLogger()->warning('[SectionRenderUtil] No mapping found for ' . $sectionType);
            throw new FirstSpiritContentTemplateException('No mapping found for ' . $sectionType);
        }
    }
}
