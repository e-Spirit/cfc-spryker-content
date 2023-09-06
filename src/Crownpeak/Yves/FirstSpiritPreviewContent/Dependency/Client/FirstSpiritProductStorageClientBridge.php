<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Dependency\Client;


use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;

/**
 * Bridge to Product Storage.
 */
class FirstSpiritProductStorageClientBridge
{
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
