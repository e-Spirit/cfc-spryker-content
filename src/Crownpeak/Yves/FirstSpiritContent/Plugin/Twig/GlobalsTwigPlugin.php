<?php

namespace Crownpeak\Yves\FirstSpiritContent\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Twig\Environment;

/**
 * Twig function to add the script tag to include the CFC Frontend API client code (static.js).
 * 
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentConfig getConfig()
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentFactory getFactory()
 */
class GlobalsTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * This is the name of the global variable that will be available in the twig templates.
     * @var string
     */
    protected const FIRSTSPIRIT_CFC_FRONTEND_API_SCRIPT = 'firstSpiritCfcScriptUrl';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig = $this->setFirstSpiritConfigEnvironmentVariables($twig);

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function setFirstSpiritConfigEnvironmentVariables(Environment $twig): Environment
    {

        $twig->addGlobal(static::FIRSTSPIRIT_CFC_FRONTEND_API_SCRIPT, $this->firstSpiritCfcScriptUrl());

        return $twig;
    }

    /**
     * The script that will be added to the twig template.
     * @return string
     */
    public function firstSpiritCfcScriptUrl(): string
    {
        $isPreview = $this->getFactory()->getPreviewService()->isPreview();
        if (!$isPreview) {
            return '';
        }

        // Add script to include CFC FE API client
        $script =  '<script src="' . $this->getConfig()->getRenderingScriptUrl() . '" data-fs-base-url="' . $this->getConfig()->getBaseRenderingScriptUrl() . '" data-fs-log-level="' . $this->getConfig()->getRenderingScriptLogLevel() . '" crossorigin="anonymous"></script>';
        $script .= '<script>';
        $currentDir = __DIR__;
        $scriptDir = realpath($currentDir . '/../../../../../../assets/Yves/js/');
        // Add scripts that handle the interaction with CFC FE API
        // TODO: Find a way to properly use Spryker asset handling / JS building pipeline
        $script .= file_get_contents($scriptDir . '/helper.js');
        $script .= file_get_contents($scriptDir . '/hooks.js');
        $script .= file_get_contents($scriptDir . '/teaser-grid.js');
        $script .= file_get_contents($scriptDir . '/css-variants.js');
        $script .= '</script>';
        return $script;
    }
}
