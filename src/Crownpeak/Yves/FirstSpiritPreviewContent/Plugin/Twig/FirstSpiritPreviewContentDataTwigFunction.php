<?php
namespace Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Twig\TwigFunction;
use Twig\Environment;

/**
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentConfig getConfig()
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentFactory getFactory()
 * @method \Spryker\Client\Product\ProductClientInterface getClient()
 */
/**
 * Twig function go set Content Url and get content data.
 */
class FirstSpiritPreviewContentDataTwigFunction extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * This is the name of the global function that will be available in the twig templates.
     * usage: {{ firstSpiritCfcContentScriptData(id, type, language) }}
     * @var string
     */
    protected const FIRSTSPIRIT_CFC_CONTENT_SCRIPT_DATA = 'firstSpiritCfcContentScriptData';

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
        $twig->addFunction(
            new TwigFunction(
                static::FIRSTSPIRIT_CFC_CONTENT_SCRIPT_DATA,
                [$this, 'firstSpiritCfcContentScriptData']
            )
        );

        return $twig;
    }

    /**
     * The data that will be queried and added to the twig template(s).
     * @param $id
     * @param $type
     * @param $language
     * @return array
     */
    public function firstSpiritCfcContentScriptData($id, $type, $language): array
    {
        $url = $this->getConfig()->getContentEndpointScript();
        $content = $this->getFactory()->getContentJsonFetcherClient()->fetchContentDataFromUrl($url, $id, $type, $language);
        return $content;
    }
}
