<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent;

use Crownpeak\Client\FirstSpiritPreviewContent\FirstSpiritPreviewContentClientInterface;
use Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentDependencyProvider;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * FirstSpiritPreviewContent Factory.
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentConfig getConfig()
 */
class FirstSpiritPreviewContentFactory extends AbstractFactory
{

    public function __construct()
    {
        $config = $this->getConfig();
        $apiHost = $config->getContentEndpointScript();
        $this->getProvidedDependency(FirstSpiritPreviewContentDependencyProvider::CONTENT_JSON_FETCHER)->setApiHost($apiHost);
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
}
