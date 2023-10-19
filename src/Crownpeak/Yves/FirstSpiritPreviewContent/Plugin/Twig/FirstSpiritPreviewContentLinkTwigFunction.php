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
     * @param mixed $linkData The link data as received from the API.
     * @return string The link URL.
     */
    public function firstSpiritGetLinkUrl($linkData): string
    {

        if (isset($linkData['data'])) {
            // External links
            if (isset($linkData['data']['lt_linkUrl'])) {
                return $linkData['data']['lt_linkUrl'];
            }
            // Content pages
            if (isset($linkData['data']['lt_pageref']) && isset($linkData['data']['lt_pageref']['referenceId'])) {
                return $linkData['data']['lt_pageref']['referenceId'];
            }
            // Product pages
            if (isset($linkData['data']['lt_product']) && isset($linkData['data']['lt_product']['value'][0])) {
                $productId = $linkData['data']['lt_product']['value'][0]['identifier'];
                $locale = $this->getLocale();
                $productStorageClient = $this->getFactory()->getProductStorageClient();
                return $productStorageClient->getProductInfoById($productId, $locale)['url'];
            }
            // Category pages
            if (isset($linkData['data']['lt_category']) && isset($linkData['data']['lt_category']['value'][0])) {
                $categoryId = $linkData['data']['lt_category']['value'][0]['identifier'];
                $locale = $this->getLocale();
                $categoryStorageClient = $this->getFactory()->getCategoryStorageClient();
                return $categoryStorageClient->getCategoryNodeById($categoryId, $locale)['url'];
            }
        }

        return '';
    }

    /**
     * Get link target.
     *
     * @param mixed $linkData The link data as received from the API.
     * @return string
     */
    public function firstSpiritGetLinkTarget($linkData): string
    {

        if (isset($linkData['data'])) {
            // External links
            if (isset($linkData['data']['lt_linkUrl'])) {
                return 'target="_blank"';
            }
        }

        return '';
    }
}
