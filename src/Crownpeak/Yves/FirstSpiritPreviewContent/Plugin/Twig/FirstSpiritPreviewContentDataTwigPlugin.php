<?php
namespace Crownpeak\Yves\FirsSpiritPreviewContent\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Twig\TwigFunction;
use Twig\Environment;


/**
 * @method \Crownpeak\Yves\FirsSpiritPreviewContent\FirstSpiritPreviewContentConfig getConfig()
 * @method \Crownpeak\Yves\FirsSpiritPreviewContent\FirstSpiritPreviewContentFactory getFactory()
 */

class FirstSpiritPreviewContentDataTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * This is the name of the global variable that will be available in the twig templates.
     * @var string
     */
    protected const FIRSTSPIRIT_CFC_CONTENT_SCRIPT_DATA = 'firstSpiritCfcContentScriptData';

    /**
     * {@inheritDoc}
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     * @api
     *
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {

        $twig = $this->setFirstSpiritContentEnvironmentVariables($twig);

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function setFirstSpiritContentEnvironmentVariables(Environment $twig): Environment
    {

        $twig->addGlobal(static::FIRSTSPIRIT_CFC_CONTENT_SCRIPT_DATA, $this->firstSpiritCfcContentScriptData());

        return $twig;
    }

    /**
     * The script that will be added to the twig template.
     * @return array
     */
    public function firstSpiritCfcContentScriptData(): array
    {
        $id = '193';
        $type= 'product';
        $language = 'en_GB';
        $url = $this->getConfig()->getContentEndpointScript();
        $content = $this->getFactory()->getContentJsonFetcherClient()->fetchContentDataFromUrl($url, $id, $type, $language);
        return $content;
    }
}
