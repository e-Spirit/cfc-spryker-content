<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Twig\TwigFunction;
use Twig\Environment;


/**
 * Twig function go get information about categories.
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentFactory getFactory()
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentConfig getConfig()
 */
class FirstSpiritPreviewContentCategoryDataTwigFunction extends AbstractPlugin implements TwigPluginInterface
{
    use LoggerTrait;

    /**
     * This is the name of the global function that will be available in the twig templates.
     * usage: {{ firstSpiritCategoryData(categoryId) }}
     * @var string
     */
    protected const FIRSTSPIRIT_CATEGORY_DATA = 'firstSpiritCategoryData';


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
                static::FIRSTSPIRIT_CATEGORY_DATA,
                [$this, 'firstSpiritCategoryData']
            )
        );

        return $twig;
    }

    /**
     * Return data for the given category.
     *
     * @param string $categoryId ID of the category to get data for.
     * @return mixed
     */
    public function firstSpiritCategoryData($categoryId): mixed
    {
        $locale = $this->getLocale();
        $categoryStorageClient = $this->getFactory()->getCategoryStorageClient();

        $categoryStorageData = $categoryStorageClient->getCategoryNodeById($categoryId, $locale);

        return !$categoryStorageData ? null : $categoryStorageData;
    }
}
