<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent;

use Crownpeak\Client\FirstSpiritPreviewContent\FirstSpiritPreviewContentClientInterface;
use Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentDependencyProvider;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * FirstSpiritPreviewContent Factory.
 */
class FirstSpiritPreviewContentFactory extends AbstractFactory
{
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
