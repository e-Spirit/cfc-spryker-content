<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Shared\Twig\TwigFunctionProvider;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Twig\TwigFunction;
use Twig\Environment;


/**
 * Twig function go set Content Url and get content data.
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentFactory getFactory()
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
    protected const FIRSTSPIRIT_CFC_CONTENT_SCRIPT_DATA = 'firstSpiritContent';

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
        $this->twig = $twig;
        $twig->addFunction(
            new TwigFunction(
                static::FIRSTSPIRIT_CFC_CONTENT_SCRIPT_DATA,
                [$this, 'firstSpiritContent']
            )
        );

        return $twig;
    }

    /**
     * The data that will be queried and added to the twig template(s).
     * @param $id
     * @param $type
     * @param $language
     * @param $slotName
     * @return string
     */
    public function firstSpiritContent($slotName): string
    {
        // $this->getLogger()->info('[FirstSpiritPreviewContentDataTwigFunction] Getting data for: ' . $type . ' ' . $id . ' Slot: ' . $slotName);
        // $data = $this->getFactory()->getContentJsonFetcherClient()->fetchContentDataFromUrl($id, $type, $language);
        $this->getLogger()->info('[FirstSpiritPreviewContentDataTwigFunction] Getting data for slot: ' . $slotName);

        $data = $this->getFactory()->getCurrentPage();
        if (empty($data) || count($data['items']) === 0) {
            // $this->getLogger()->info('[FirstSpiritPreviewContentDataTwigFunction] No items found for: ' . $type . ' ' . $id);
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

            $renderedBlock = '';
            // try {
            $template = $this->getTemplateForSection($section);
            $this->getLogger()->info('[FirstSpiritPreviewContentDataTwigFunction] Attempting to render section ' . $section['previewId'] . ' with template ' . $template);
            $renderedBlock = $this->twig->render('@CmsBlock/template/fs_content_block.twig', [
                'fsData' => $section,
                'template' => $template
            ]);
            $this->getLogger()->info('[FirstSpiritPreviewContentDataTwigFunction] Finished rendering section' . $section['previewId']);
            // } catch (\Throwable $throwable) {
            //     $this->getLogger()->error('[FirstSpiritPreviewContentDataTwigFunction] Failed to render section ' . $section['previewId']);

            //     //     // if ($this->factory->getConfig()->shouldDisplayBlockRenderErrors()) {
            //     //     //     $renderedBlock = (new RenderErrorFormatter($twig))->format($throwable);
            //     //     // }
            //     $this->getLogger()->error(sprintf(
            //         "[FirstSpiritPreviewContentDataTwigFunction] Error during rendering of CMS blocks with options: %s\n%s",
            //         $throwable->getMessage(),
            //         $throwable->getTraceAsString()
            //     ));
            // }

            // if ($isContentEditable) {
            $renderedContent .= $this->decorateSection($renderedBlock, $section['previewId']);
            // } else {
            //     $renderedContent .= $renderedBlock;
            // }
        }
        return $this->decorateSlot($renderedContent, $slotName);
    }


    /**
     * @param string $content
     * @param string $previewId
     * @return string
     */
    public function decorateSection(string $content, string $previewId = ''): string
    {
        $decoratedContent = '<div';
        if (!empty($previewId)) {
            $decoratedContent .= ' data-preview-id="' . $previewId . '"';
        }
        return $decoratedContent . '>' . $content . '</div>';
    }
    /**
     * @param string $content
     * @param string $slotName
     * @return string
     */
    public function decorateSlot(string $content, string $slotName): string
    {
        return '<div data-fcecom-slot-name="' . $slotName . '">' . $content . '</div>';
    }

    private function getTemplateForSection($section): string
    {
        switch ($section['sectionType']) {
            case 'text_image':
                return 'fs-text-image';
        }
        return 'fs-data-visualizer';
    }
}
