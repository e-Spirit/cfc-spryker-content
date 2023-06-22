<?php
namespace Crownpeak\Yves\FirstSpiritPreviewCaaS\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Twig\TwigFunction;
use Twig\Environment;


/**
 * @method \Crownpeak\Yves\FirstSpiritPreviewCaaS\FirstSpiritPreviewCaaSConfig getConfig()
 * @method \Crownpeak\Yves\FirstSpiritPreviewCaaS\FirstSpiritPreviewCaaSFactory getFactory()
 */

class FirstSpiritPreviewCaaSDataTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * This is the name of the global variable that will be available in the twig templates.
     * @var string
     */
    protected const FIRSTSPIRIT_CFC_CAAS_SCRIPT_DATA = 'firstSpiritCfcCaaSScriptData';

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

        $twig = $this->setFirstSpiritCaaSEnvironmentVariables($twig);

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function setFirstSpiritCaaSEnvironmentVariables(Environment $twig): Environment
    {

        $twig->addGlobal(static::FIRSTSPIRIT_CFC_CAAS_SCRIPT_DATA, $this->firstSpiritCfcScriptData());

        return $twig;
    }

    /**
     * The script that will be added to the twig template.
     * @return array
     */
    public function firstSpiritCfcScriptData(): array
    {
        $id = '193';
        $type= 'product';
        $language = 'en_GB';
        $url = $this->getConfig()->getCaasEndpointScript();
        $content = $this->getFactory()->getCaaSJsonFetcherClient()->fetchCaaSDataFromUrl($url, $id, $type, $language);
        return $content;
    }
}
