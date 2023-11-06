<?php

namespace Crownpeak\Yves\FirstSpiritContent\Dependency\Client;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Spryker\Client\CategoryStorage\CategoryStorageClientInterface;
use Spryker\Shared\Kernel\Store;

/**
 * Bridge impl to category storage.
 */
class CategoryStorageClientBridge
{
    /**
     * @var CategoryStorageClientInterface $categoryStorageClient
     */
    protected $categoryStorageClient;

    /**
     * @param CategoryStorageClientInterface $categoryStorageClient
     */
    public function __construct(CategoryStorageClientInterface $categoryStorageClient)
    {
        $this->categoryStorageClient = $categoryStorageClient;
    }

    /**
     * @param int $categoryNodeId
     * @param string $localeName
     * @return CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById(int $categoryNodeId, string $localeName): CategoryNodeStorageTransfer
    {
        return $this->categoryStorageClient->getCategoryNodeById($categoryNodeId, $localeName, Store::getInstance()->getStoreName());
    }
}
