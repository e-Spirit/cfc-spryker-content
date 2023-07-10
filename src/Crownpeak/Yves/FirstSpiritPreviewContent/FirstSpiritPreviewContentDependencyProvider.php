<?php
namespace Crownpeak\Yves\FirstSpiritPreviewContent;

use Crownpeak\Client\FirstSpiritPreviewContent\FirstSpiritPreviewContentClient;
use Crownpeak\Client\FirstSpiritPreviewContent\FirstSpiritPreviewContentClientInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

/**
 * Dependency Provider.
 */
class FirstSpiritPreviewContentDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CONTENT_JSON_FETCHER = 'CONTENT_JSON_FETCHER';

    public function provideDependencies(Container $container): Container
    {
        $container = $this->addContentJsonFetcherClient($container);

        return $container;
    }

    protected function addContentJsonFetcherClient(Container $container): Container
    {
        $container->set(static::CONTENT_JSON_FETCHER, function (Container $container) {
            return new FirstSpiritPreviewContentClient();
        });

        return $container;
    }
}
