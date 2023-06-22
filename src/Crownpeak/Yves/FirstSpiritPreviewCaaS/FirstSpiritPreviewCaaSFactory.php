<?php
namespace Crownpeak\Yves\FirstSpiritPreviewCaaS;

use Crownpeak\Client\FirstSpiritPreviewCaaS\FirstSpiritPreviewCaaSClientInterface;
use Crownpeak\Yves\FirstSpiritPreviewCaaS\FirstSpiritPreviewCaaSDependencyProvider;
use Spryker\Yves\Kernel\AbstractFactory;

class FirstSpiritPreviewCaaSFactory extends AbstractFactory
{
    /**
     * @return \Crownpeak\Client\FirstSpiritPreviewCaaS\FirstSpiritPreviewCaaSClientInterface
     */
    public function getCaaSJsonFetcherClient(): FirstSpiritPreviewCaaSClientInterface
    {
        return $this->getProvidedDependency(FirstSpiritPreviewCaaSDependencyProvider::CAAS_JSON_FETCHER);
    }
}
