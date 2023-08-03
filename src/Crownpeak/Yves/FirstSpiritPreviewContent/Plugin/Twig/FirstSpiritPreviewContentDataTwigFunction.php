<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Twig\TwigFunction;
use Twig\Environment;


/**
 * Twig function go set Content Url and get content data.
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentFactory getFactory()
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentConfig getConfig()
 */
class FirstSpiritPreviewContentDataTwigFunction extends AbstractPlugin implements TwigPluginInterface
{
    use LoggerTrait;

    private Environment $twig;

    /**
     * This is the name of the global function that will be available in the twig templates.
     * usage: {{ firstSpiritContent(slotName) }}
     * @var string
     */
    protected const FIRSTSPIRIT_CONTENT = 'firstSpiritContent';

    /**
     * This is the name of the global function that will be available in the twig templates.
     * usage: {{ firstSpiritContent(slotName) }}
     * @var string
     */
    protected const FIRSTSPIRIT_RICHT_TEXT = 'firstSpiritRichText';


    protected FirstSpiritSectionRenderUtil $sectionRenderUtil;

    /**
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $this->sectionRenderUtil = new FirstSpiritSectionRenderUtil(
            $twig,
            $this->getFactory(),
            $this->getConfig()
        );

        $this->twig = $twig;
        $twig->addFunction(
            new TwigFunction(
                static::FIRSTSPIRIT_CONTENT,
                [$this, 'firstSpiritContent']
            )
        );

        $twig->addFunction(
            new TwigFunction(
                static::FIRSTSPIRIT_RICHT_TEXT,
                [$this, 'firstSpiritRichText']
            )
        );

        return $twig;
    }

    /**
     * Returns the rendered content for the slot with the given name.
     * Accesses the data based on what has been fetched earlier using FirstSpiritAttributesTwigFunction.
     *
     * @param string $slotName The name of the slot to render.
     * @return string The content to be inserted into the DOM.
     */
    public function firstSpiritContent($slotName): string
    {
        $this->getLogger()->info('[FirstSpiritPreviewContentDataTwigFunction] Getting data for slot: ' . $slotName);

        $data = $this->getFactory()->getDataStore()->getCurrentPage();
        if (is_null($data)) {
            $error = $this->getFactory()->getDataStore()->getError();
            if (!is_null($error)) {
                $this->getLogger()->info('[FirstSpiritPreviewContentDataTwigFunction] Rendering error for slot: ' . $slotName);
                return $this->decorateSlot($this->sectionRenderUtil->getErrorMessage($error), $slotName);
            }
        }

        if (empty($data) || count($data['items']) === 0) {
            $this->getLogger()->info('[FirstSpiritPreviewContentDataTwigFunction] No items found');
            return $this->decorateSlot('', $slotName);
        }
        $pageContent = $data['items'][0];
        $slotContent = NULL;

        foreach ($pageContent['children'] as $slot) {
            if ($slot['name'] === $slotName) {
                $slotContent = $slot;
                break;
            }
        }


        if (is_null($slotContent)) {
            $this->getLogger()->warning('[FirstSpiritPreviewContentDataTwigFunction] Slot not found: ' . $slotName);
            return '';
        }


        $renderedContent = '';

        $this->getLogger()->info('[FirstSpiritPreviewContentDataTwigFunction] Found ' . count($slotContent['children']) . ' sections to render');

        foreach ($slotContent['children'] as $section) {
            $renderedBlock = $this->sectionRenderUtil->renderSection($section);
            $renderedContent .= $this->decorateSection($renderedBlock, $section['previewId']);
        }
        return $this->decorateSlot($renderedContent, $slotName);
    }


    /**
     * Render rich text with format.
     *
     * @param mixed $content The content as received from the API.
     * @return string
     */
    public function firstSpiritRichText($content): string
    {
        $richTextUtil = new FirstSpiritRichTextUtil();
        return $richTextUtil->renderRichText($content);
    }

    /**
     * Decorates the section by wrapping it into a container with the preview ID set when in preview.
     *
     * @param string $content The sections content to wrap.
     * @param string $previewId The preview ID of the section.
     * @return string
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
     * @return string
     */
    public function decorateSlot(string $content, string $slotName): string
    {
        $isPreview = $this->getFactory()->getPreviewService()->isPreview();
        if ($isPreview) {
            return '<div data-fcecom-slot-name="' . $slotName . '">' . $content . '</div>';
        }
        return $content;
    }
}
