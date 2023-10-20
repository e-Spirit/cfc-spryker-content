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
     * @param mixed $link The link data as received from the API.
     * @return string The link URL.
     */
    public function firstSpiritGetLinkUrl($link): string
    {
        $linkData = $this->extractLinkData($link);


        $this->getLogger()->info('[FirstSpiritPreviewLinkTwigFunction] Getting URL for ' . json_encode($linkData));

        if (isset($linkData)) {
            // External links
            if (isset($linkData['lt_linkUrl'])) {
                return $linkData['lt_linkUrl'];
            }

            // Content pages
            if (isset($linkData['lt_pageref']) && isset($linkData['lt_pageref']['referenceId'])) {
                // TODO: Move code from ContentPageController so we do not have to perform a request against our own server
                $pageId = $linkData['lt_pageref']['referenceId'];

                // Determine origin of application
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'];
                $origin = $protocol . '://' . $host;

                // Build the request with the required parameters
                $params = http_build_query([
                    'pageId' => $pageId,
                    'locale' => $this->getLocale(),
                ]);
                $request = $origin . '/getContentPageUrl?' . $params;

                try {
                    // Initialize cURL session
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $request);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    $response = curl_exec($ch);
                    curl_close($ch);

                    return json_decode($response)->url;
                } catch (\Throwable $th) {
                    $this->getLogger()->error('[FirstSpiritPreviewLinkTwigFunction] Failed to get URL via cURL: ' . $th->getMessage());
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
        $linkData = $link['data'];
        while (isset($linkData['data'])) {
            $linkData = $linkData['data'];
        }
        return $linkData;
    }
}
