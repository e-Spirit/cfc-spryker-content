<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent;

use Crownpeak\Client\FirstSpiritPreviewContent\FirstSpiritPreviewContentClient;
use Crownpeak\Yves\FirstSpiritPreviewContent\Dependency\Client\FirstSpiritSessionClientBridge;
use Crownpeak\Yves\FirstSpiritPreviewContent\Dependency\Client\FirstSpiritStorageClientBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

/**
 * Dependency Provider.
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentConfig getConfig()
 */
class FirstSpiritPreviewContentDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CONTENT_JSON_FETCHER = 'CONTENT_JSON_FETCHER';
    public const CLIENT_SESSION = 'CLIENT_SESSION';
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';


    public function provideDependencies(Container $container): Container
    {
        $container = $this->addContentJsonFetcherClient($container);
        $container = $this->addSessionClient($container);
        $container = $this->addStorageClient($container);

        return $container;
    }



    protected function addStorageClient(Container $container): Container
    {
        $container[static::CLIENT_STORAGE] = function (Container $container) {
            return new FirstSpiritStorageClientBridge($container->getLocator()->storage()->client(), $this->getConfig());
        };

        return $container;
    }

    protected function addContentJsonFetcherClient(Container $container): Container
    {
        $container->set(static::CONTENT_JSON_FETCHER, function (Container $container) {
            return new FirstSpiritPreviewContentClient();
        });

        return $container;
    }

    /**
     * @param Container $container
     * @return Container
     * @throws FrozenServiceException
     */
    public function addSessionClient(Container $container): Container
    {
        $container->set(static::CLIENT_SESSION, function (Container $container) {
            return new FirstSpiritSessionClientBridge($container->getLocator()->session()->client());
        });
        return $container;
    }
}
