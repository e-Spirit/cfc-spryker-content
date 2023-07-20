<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
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
    /**
     * This is the name of the global function that will be available in the twig templates.
     * usage: {{ firstSpiritContent(id, type, language) }}
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
     * @return array
     */
    public function firstSpiritContent($id, $type, $language): array
    {
        $content = $this->getFactory()->getContentJsonFetcherClient()->fetchContentDataFromUrl($id, $type, $language);
        return $content;
    }
}
