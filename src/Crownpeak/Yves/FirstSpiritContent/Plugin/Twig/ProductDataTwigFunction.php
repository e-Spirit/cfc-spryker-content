<?php

namespace Crownpeak\Yves\FirstSpiritContent\Plugin\Twig;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Twig\TwigFunction;
use Twig\Environment;


/**
 * Twig function to get information about products.
 *
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentFactory getFactory()
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentConfig getConfig()
 */
class ProductDataTwigFunction extends AbstractPlugin implements TwigPluginInterface
{
    use LoggerTrait;


    /**
     * This is the name of the global function that will be available in the twig templates.
     * usage: {{ firstSpiritProductData(productId) }}
     * @var string
     */
    protected const FIRSTSPIRIT_PRODUCT_DATA = 'firstSpiritProductData';


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
                static::FIRSTSPIRIT_PRODUCT_DATA,
                [$this, 'firstSpiritProductData']
            )
        );

        return $twig;
    }

    /**
     * Return data for the given product.
     *
     * @param string $productId ID of the product to get data for.
     * @return ?ProductViewTransfer The Spryker product object.
     */
    public function firstSpiritProductData(string $productId): ?ProductViewTransfer
    {
        $locale = $this->getLocale();
        $productStorageClient = $this->getFactory()->getProductStorageClient();

        return $productStorageClient->getProductInfoById($productId, $locale);
    }
}
