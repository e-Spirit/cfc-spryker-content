<?php
namespace Crownpeak\Yves\FirstSpiritPreviewCaaS;

use Crownpeak\Client\FirstSpiritPreviewCaaS\FirstSpiritPreviewCaaSClient;
use Crownpeak\Client\FirstSpiritPreviewCaaS\FirstSpiritPreviewCaaSClientInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class FirstSpiritPreviewCaaSDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CAAS_JSON_FETCHER = 'CAAS_JSON_FETCHER';

    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCaaSJsonFetcherClient($container);

        return $container;
    }

    protected function addCaaSJsonFetcherClient(Container $container): Container
    {
        $container->set(static::CAAS_JSON_FETCHER, function (Container $container) {
            return new FirstSpiritPreviewCaaSClient();
        });

        return $container;
    }
}
