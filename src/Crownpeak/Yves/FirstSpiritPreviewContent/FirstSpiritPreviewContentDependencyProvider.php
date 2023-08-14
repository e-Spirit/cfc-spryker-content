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
    public const CLIENT_SESSION = 'CLIENT_SESSION';
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';


    public function provideDependencies(Container $container): Container
    {
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
