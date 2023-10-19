<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Dependency\Client;


use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;

/**
 * Bridge to Product Storage.
 */
class FirstSpiritProductStorageClientBridge
{

  protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';
  protected const ID_PRODUCT_ABSTRACT_KEY = 'id_product_abstract';

  /**
   * @var ProductStorageClientInterface $productStorageClient
   */
  protected $productStorageClient;

  /**
   * @param $productClient
   */
  public function __construct($productClient)
  {
    $this->productStorageClient = $productClient;
  }

  public function getProductInfoById(string $identifier, string $localeName): ?ProductViewTransfer
  {
    $productStorageData = $this->findProductAbstractStorageDataByMapping(
      static::PRODUCT_ABSTRACT_MAPPING_TYPE,
      $identifier,
      $localeName
    );

    if (!$productStorageData) {
      return null;
    }

    $productViewTransfer = $this
      ->findProductAbstractViewTransfer($productStorageData[self::ID_PRODUCT_ABSTRACT_KEY], $localeName);

    // Note that the object seems to have no content when used with json_encode(), but you may access properties like price and images
    return !$productViewTransfer ? null : $productViewTransfer;
  }

  /**
   * @param string $mappingType
   * @param string $identifier
   * @param string $localeName
   * @return mixed
   */
  public function findProductAbstractStorageDataByMapping(string $mappingType, string $identifier, string $localeName): ?array
  {
    return $this->productStorageClient->findProductAbstractStorageDataByMapping($mappingType, $identifier, $localeName);
  }

  /**
   * @param int $idProductAbstract
   * @param string $localeName
   * @param array $selectedAttributes
   * @return mixed
   */
  public function findProductAbstractViewTransfer(int $idProductAbstract, string $localeName, array $selectedAttributes = []): ?ProductViewTransfer
  {
    return $this->productStorageClient->findProductAbstractViewTransfer($idProductAbstract, $localeName, $selectedAttributes);
  }

  /**
   * @param int $idProductAbstract
   * @param string $localeName
   * @return array|null
   */
  public function findProductAbstractStorageData(int $idProductAbstract, string $localeName): ?array
  {
    return $this->productStorageClient->findProductAbstractStorageData($idProductAbstract, $localeName);
  }
}
