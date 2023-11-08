<?php

namespace Crownpeak\Yves\FirstSpiritContent;

use Crownpeak\Client\FirstSpiritContent\FirstSpiritContentClient;
use Crownpeak\Client\FirstSpiritContent\FrontendApiServerClient;
use Crownpeak\Client\FirstSpiritContent\FrontendApiServerClientInterface;
use Crownpeak\Yves\FirstSpiritContent\Dependency\Client\SessionClientBridge;
use Crownpeak\Yves\FirstSpiritContent\PreviewService;
use Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentDependencyProvider;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * Factory for the Content module.
 * 
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentConfig getConfig()
 */
class FirstSpiritContentFactory extends AbstractFactory
{
    use LoggerTrait;

    private PreviewService $previewService;
    private FirstSpiritElementDataStore $dataStore;
    private FrontendApiServerClientInterface $apiClient;

    public function __construct()
    {
        $this->previewService = new PreviewService($this);
        $this->dataStore = new FirstSpiritElementDataStore();
        $config = $this->getConfig();
        $apiHost = $config->getContentEndpointScript();
        $this->apiClient = new FrontendApiServerClient($apiHost);
    }


    /**
     * @return PreviewService The current instance of the PreviewService.
     */
    public function getPreviewService(): PreviewService
    {
        return $this->previewService;
    }

    /**
     * @return FrontendApiServerClientInterface The current instance of the FrontendApiServerClient.
     */
    public function getContentJsonFetcherClient(): FrontendApiServerClientInterface
    {
        return $this->apiClient;
    }

    /**
     * Sets the referer to the JSON client.
     * 
     * @param string $referer The value to set.
     */
    public function setReferer(string $referer)
    {
        $this->apiClient->setReferer($referer);
    }

    /**
     * @return SessionClientBridge
     * @throws ContainerKeyNotFoundException
     */
    public function getSessionClient(): SessionClientBridge
    {
        return $this->getProvidedDependency(FirstSpiritContentDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return FirstSpiritContentDataStore
     */
    public function getDataStore()
    {
        return $this->dataStore;
    }

    /**
     * @return FirstSpiritStorageClientBridge
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(FirstSpiritContentDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return FirstSpiritProductStorageClient
     * @throws ContainerKeyNotFoundException
     */
    public function getProductStorageClient()
    {
        return $this->getProvidedDependency(FirstSpiritContentDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return FirstSpiritCategoryStorageClientInterface
     * @throws ContainerKeyNotFoundException
     */
    public function getCategoryStorageClient()
    {
        return $this->getProvidedDependency(FirstSpiritContentDependencyProvider::CLIENT_CATEGORY_STORAGE);
    }
}
