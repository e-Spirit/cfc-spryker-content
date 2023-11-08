<?php

namespace Crownpeak\Yves\FirstSpiritContent\Dependency\Client;


use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;

/**
 * Bridge implementation to product storage.
 * Used to retrieve Spryker products.
 */
class ProductStorageClientBridge
{

    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';
    protected const ID_PRODUCT_ABSTRACT_KEY = 'id_product_abstract';

    protected ProductStorageClientInterface $productStorageClient;

    /**
     * @param $productClient
     */
    public function __construct(ProductStorageClientInterface $productClient)
    {
        $this->productStorageClient = $productClient;
    }

    /**
     * Returns the Spryker product object with the given ID.
     * 
     * @param string $identifier The product identifier.
     * @param string $locale The locale to get the product object in.
     * @param ?ProductViewTransfer The product object.
     */
    public function getProductInfoById(string $identifier, string $locale): ?ProductViewTransfer
    {
        $productStorageData = $this->findProductAbstractStorageDataByMapping(
            static::PRODUCT_ABSTRACT_MAPPING_TYPE,
            $identifier,
            $locale
        );

        if (!$productStorageData) {
            return null;
        }

        $productViewTransfer = $this
            ->findProductAbstractViewTransfer($productStorageData[self::ID_PRODUCT_ABSTRACT_KEY], $locale);

        // Note that the object seems to have no content when used with json_encode(), but you may access properties like price and images
        return !$productViewTransfer ? null : $productViewTransfer;
    }

    /**
     * @param string $mappingType
     * @param string $identifier
     * @param string $localeName
     * @return mixed
     */
    private function findProductAbstractStorageDataByMapping(string $mappingType, string $identifier, string $localeName): ?array
    {
        return $this->productStorageClient->findProductAbstractStorageDataByMapping($mappingType, $identifier, $localeName);
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     * @param array $selectedAttributes
     * @return mixed
     */
    private function findProductAbstractViewTransfer(int $idProductAbstract, string $localeName, array $selectedAttributes = []): ?ProductViewTransfer
    {
        return $this->productStorageClient->findProductAbstractViewTransfer($idProductAbstract, $localeName, $selectedAttributes);
    }
}
