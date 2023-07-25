<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent;

use Crownpeak\Client\FirstSpiritPreviewContent\FirstSpiritPreviewContentClientInterface;
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

    private mixed $currentPageData;

    public function __construct()
    {
        $this->currentPageData = NULL;
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

    /**
     * Sets the FS data for the current page.
     * 
     * @param mixed $data The FS data for the current page. Null if no pendant exists.
     */
    public function setCurrentPage(mixed $data)
    {
        if (empty($data) || count($data['items']) === 0) {
            $this->getLogger()->warning('[FirstSpiritPreviewContentFactory] Not setting empty result');
            $this->currentPageData = NULL;
        } else {
            $this->getLogger()->info('[FirstSpiritPreviewContentFactory] Setting data for current page ' . $data['items'][0]['previewId']);
            $this->currentPageData = $data;
        }
    }

    /**
     * Gets the FS data for the current page.
     * 
     */
    public function getCurrentPage()
    {
        if (!is_null($this->currentPageData)) {
            $this->getLogger()->info('[FirstSpiritPreviewContentFactory] Getting data for current page ' . $this->currentPageData['items'][0]['previewId']);
            return $this->currentPageData;
        }
        $this->getLogger()->warning('[FirstSpiritPreviewContentFactory] No data set for current page');
        return NULL;
    }
}
