<?php

namespace Crownpeak\Yves\FirstSpiritContent\Dependency\Client;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Spryker\Client\CategoryStorage\CategoryStorageClientInterface;
use Spryker\Shared\Kernel\Store;

/**
 * Bridge implementation to category storage.
 * Used to retrieve Spryker categories.
 */
class CategoryStorageClientBridge
{
    protected CategoryStorageClientInterface $categoryStorageClient;

    /**
     * @param CategoryStorageClientInterface $categoryStorageClient
     */
    public function __construct(CategoryStorageClientInterface $categoryStorageClient)
    {
        $this->categoryStorageClient = $categoryStorageClient;
    }

    /**
     * Returns the Spryker category object.
     * 
     * @param int $categoryNodeId ID of the category.
     * @param string $locale Locale to get the category object in.
     * @return CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById(int $categoryNodeId, string $locale): CategoryNodeStorageTransfer
    {
        return $this->categoryStorageClient->getCategoryNodeById($categoryNodeId, $locale, Store::getInstance()->getStoreName());
    }
}
