<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent;

use Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewService;
use Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentDataStore;
use Crownpeak\Client\FirstSpiritPreviewContent\FirstSpiritPreviewContentClientInterface;
use Crownpeak\Yves\FirstSpiritPreviewContent\Dependency\Client\FirstSpiritSessionClientBridgeInterface;
use Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentDependencyProvider;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * FirstSpiritPreviewContent Factory.
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentConfig getConfig()
 */
class FirstSpiritPreviewContentFactory extends AbstractFactory
{
    use LoggerTrait;

    private FirstSpiritPreviewService $previewService;
    private FirstSpiritPreviewContentDataStore $dataStore;

    public function __construct()
    {
        $this->previewService = new FirstSpiritPreviewService($this);
        $this->dataStore = new FirstSpiritPreviewContentDataStore();
        $config = $this->getConfig();
        $apiHost = $config->getContentEndpointScript();
        $this->getProvidedDependency(FirstSpiritPreviewContentDependencyProvider::CONTENT_JSON_FETCHER)->setApiHost($apiHost);
    }


    /**
     * @return FirstSpiritPreviewService The current instance of the FirstSpiritPreviewService.
     */
    public function getPreviewService(): FirstSpiritPreviewService
    {
        return $this->previewService;
    }


    /**
     * @return \Crownpeak\Client\FirstSpiritPreviewContent\FirstSpiritPreviewContentClientInterface
     */
    public function getContentJsonFetcherClient(): FirstSpiritPreviewContentClientInterface
    {
        return $this->getProvidedDependency(FirstSpiritPreviewContentDependencyProvider::CONTENT_JSON_FETCHER);
    }

    /**
     * Sets the referer to the JSON client.
     * 
     * @param string $referer The value to set.
     */
    public function setReferer(string $referer)
    {
        $this->getProvidedDependency(FirstSpiritPreviewContentDependencyProvider::CONTENT_JSON_FETCHER)->setReferer($referer);
    }



    /**
     * @return FirstSpiritSessionClientBridgeInterface
     * @throws ContainerKeyNotFoundException
     */
    public function getSessionClient(): FirstSpiritSessionClientBridgeInterface
    {
        return $this->getProvidedDependency(FirstSpiritPreviewContentDependencyProvider::CLIENT_SESSION);
    }


    /**
     * @return FirstSpiritPreviewContentDataStore
     */
    public function getDataStore()
    {
        return $this->dataStore;
    }
}
