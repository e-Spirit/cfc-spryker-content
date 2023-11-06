<?php

namespace Crownpeak\Yves\FirstSpiritContent\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Twig\TwigFunction;
use Twig\Environment;


/**
 * Twig function to get information about categories.
 *
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentFactory getFactory()
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentConfig getConfig()
 */
class CategoryDataTwigFunction extends AbstractPlugin implements TwigPluginInterface
{
    use LoggerTrait;

    /**
     * This is the name of the global function that will be available in the twig templates.
     * usage: {{ firstSpiritCategoryData(categoryId) }}
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
     * @return ?array The Spryker category object.
     */
    public function firstSpiritCategoryData(string $categoryId): ?array
    {
        $locale = $this->getLocale();
        $categoryStorageClient = $this->getFactory()->getCategoryStorageClient();

        $categoryStorageData = $categoryStorageClient->getCategoryNodeById($categoryId, $locale);

        return !$categoryStorageData ? null : $categoryStorageData;
    }
}
