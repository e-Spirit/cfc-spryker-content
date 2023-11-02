<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig;

use Crownpeak\Shared\FirstSpiritPreviewContent\FirstSpiritContentPageUtil;
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
class FirstSpiritPreviewContentLinkTwigFunction extends AbstractPlugin implements TwigPluginInterface
{
    use LoggerTrait;

    /**
     * This is the name of the global function that will be available in the twig templates.
     * usage: {{ firstSpiritGetLinkUrl(linkData) }}
     * @var string
     */
    protected const FIRSTSPIRIT_GET_LINK_URL = 'firstSpiritGetLinkUrl';

    /**
     * This is the name of the global function that will be available in the twig templates.
     * usage: {{ firstSpiritGetLinkTarget(linkData) }}
     * @var string
     */
    protected const FIRSTSPIRIT_GET_LINK_TARGET = 'firstSpiritGetLinkTarget';


    protected FirstSpiritSectionRenderUtil $sectionRenderUtil;
    protected FirstSpiritContentPageUtil $contentPageUtil;

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
        $this->contentPageUtil = new FirstSpiritContentPageUtil($this->getFactory());

        $twig->addFunction(
            new TwigFunction(
                static::FIRSTSPIRIT_GET_LINK_URL,
                [$this, 'firstSpiritGetLinkUrl']
            )
        );

        $twig->addFunction(
            new TwigFunction(
                static::FIRSTSPIRIT_GET_LINK_TARGET,
                [$this, 'firstSpiritGetLinkTarget']
            )
        );

        return $twig;
    }

    /**
     * Get URL for the given link.
     * Resolves product and category links with Spryker data.
     * Resolves content page links with FS data.
     *
     * @param mixed $link The link data as received from the API.
     * @return string The link URL.
     */
    public function firstSpiritGetLinkUrl($link): string
    {
        $this->getLogger()->debug('[FirstSpiritPreviewLinkTwigFunction] Getting URL for ' . json_encode($link));

        $linkData = $this->extractLinkData($link);

        if (isset($linkData)) {
            // External links
            if (isset($linkData['lt_linkUrl'])) {
                return $linkData['lt_linkUrl'];
            }

            // Content pages
            if (isset($linkData['lt_pageref']) && isset($linkData['lt_pageref']['referenceId'])) {
                $pageId = $linkData['lt_pageref']['referenceId'];
                $url = $this->contentPageUtil->getUrl($pageId, $this->getLocale());

                if (is_null($url)) {
                    $this->getLogger()->warning('[FirstSpiritPreviewLinkTwigFunction] Cannot get URL for content page ' . $pageId);
                } else {
                    return $url;
                }
            }
            // Product pages
            if (isset($linkData['lt_product']) && isset($linkData['lt_product']['value'][0])) {
                $productId = $linkData['lt_product']['value'][0]['identifier'];
                $locale = $this->getLocale();
                $productStorageClient = $this->getFactory()->getProductStorageClient();
                return $productStorageClient->getProductInfoById($productId, $locale)['url'];
            }
            // Category pages
            if (isset($linkData['lt_category']) && isset($linkData['lt_category']['value'][0])) {
                $categoryId = $linkData['lt_category']['value'][0]['identifier'];
                $locale = $this->getLocale();
                $categoryStorageClient = $this->getFactory()->getCategoryStorageClient();
                return $categoryStorageClient->getCategoryNodeById($categoryId, $locale)['url'];
            }
        }

        $this->getLogger()->warning('[FirstSpiritPreviewLinkTwigFunction] Unable to get URL for extracted data ' . json_encode($linkData));
        return '';
    }

    /**
     * Get link target.
     *
     * @param mixed $link The link data as received from the API.
     * @return string
     */
    public function firstSpiritGetLinkTarget($link): string
    {

        $linkData = $this->extractLinkData($link);
        if (isset($linkData)) {
            // External links
            if (isset($linkData['lt_linkUrl'])) {
                return 'target="_blank"';
            }
        }

        return '';
    }

    /**
     * Extract the deepest nested 'data' value from the given link.
     * Necessary, because different types of links have the important data on different nesting levels.
     * 
     * @param mixed $link The link data to extract from.
     * @return mixed The deepest nested 'data'.
     */
    private function extractLinkData(mixed $link)
    {

        foreach ($link['data'] as $key => $value) {
            if ($key == 'type' && $value == 'Link') {
                // Links within DOM Editor
                return $link['data']['data'];
                return $this->extractLinkData($link['data']);
            }

            if (isset($value['type']) && $value['type'] == 'Link') {
                return $this->extractLinkData($value);
            }
        }
        return $link['data'];
    }
}
