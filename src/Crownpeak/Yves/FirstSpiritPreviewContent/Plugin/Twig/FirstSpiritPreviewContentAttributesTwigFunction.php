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
class FirstSpiritPreviewContentAttributesTwigFunction extends AbstractPlugin implements TwigPluginInterface
{
    use LoggerTrait;

    private Environment $twig;

    /**
     * This is the name of the global function that will be available in the twig templates.
     * usage: {{ firstSpiritAttributes(id, type, template, title, locale) }}
     * @var string
     */
    protected const FIRSTSPIRIT_CFC_CONTENT_SCRIPT_DATA = 'firstSpiritAttributes';

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
                [$this, 'firstSpiritAttributes']
            )
        );

        return $twig;
    }

    /**
     * The data that will be queried and added to the twig template(s).
     * @param $id
     * @param $type
     * @param $template
     * @param $title
     * @param $locale
     * @return string
     */
    public function firstSpiritAttributes($id, $type, $template, $title, $locale): string
    {
        $isPreview = $this->getFactory()->getPreviewService()->isPreview();

        $cacheKey = md5($id . $type . $locale . ($isPreview ? 'preview' : 'release'));

        $this->getLogger()->info('[FirstSpiritPreviewContentAttributesTwigFunction] Setting attributes for: ' . $type . ' ' . $id . ' (Preview=' . $isPreview . ')');

        $data = null;
        if (!$isPreview && $this->getFactory()->getStorageClient()->hasApiResponse($cacheKey)) {
            // Check for entry in cache
            $data = $this->getFactory()->getStorageClient()->getApiResponse($cacheKey);
        } else {
            // If not in cache or in preview mode, query
            try {
                $data = $this->getFactory()->getContentJsonFetcherClient()->fetchContentDataFromUrl($id, $type, $locale);
                $this->getFactory()->getStorageClient()->setApiResponse($cacheKey, $data);
            } catch (\Throwable $th) {
                $this->getLogger()->error('[FirstSpiritPreviewContentAttributesTwigFunction] Cannot get data for: ' . $type . ' ' . $id . ' (Preview=' . $isPreview . ')');
                $this->getLogger()->error('[FirstSpiritPreviewContentAttributesTwigFunction] ' . $th->getMessage());
                $this->getFactory()->getDataStore()->setCurrentPage(null);
                $this->getFactory()->getDataStore()->setError($th);
                return '';
            }
        }

        $previewId = null;
        if (empty($data) || count($data['items']) === 0) {
            $this->getLogger()->info('[FirstSpiritPreviewContentAttributesTwigFunction] No items found for: ' . $type . ' ' . $id);
        } else {
            $pageContent = $data['items'][0];

            $previewId = $pageContent['previewId'];

            if (!isset($previewId)) {
                $this->getLogger()->error('[FirstSpiritPreviewContentAttributesTwigFunction] No preview ID found');
            } else {
                // If data is found, save it to factory to access it in other Twig functions later
                $this->getFactory()->getDataStore()->setCurrentPage($data);
            }
        }

        // Only print attributes when in preview mode as they are only required by FE API in client
        if (!$isPreview) {
            return '';
        }

        return printf(
            'data-fs-preview-id="%s" data-fs-page-id="%s" data-fs-page-type="%s" data-fs-page-template="%s" data-fs-name-%s="%s" data-fs-lang="%s"',
            $previewId,
            $id,
            $type,
            $template,
            $locale,
            $title,
            $locale
        );
    }
}
