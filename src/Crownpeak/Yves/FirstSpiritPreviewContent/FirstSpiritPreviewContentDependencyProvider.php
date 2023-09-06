<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent;

use Crownpeak\Yves\FirstSpiritPreviewContent\Dependency\Client\FirstSpiritCategoryStorageClientBridge;
use Crownpeak\Yves\FirstSpiritPreviewContent\Dependency\Client\FirstSpiritProductStorageClientBridge;
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
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    public const CLIENT_CATEGORY_STORAGE = 'CLIENT_CATEGORY_STORAGE';


    public function provideDependencies(Container $container): Container
    {
        $container = $this->addSessionClient($container);
        $container = $this->addStorageClient($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addCategoryStorageClient($container);
        return $container;
    }

    protected function addProductStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new FirstSpiritProductStorageClientBridge($container->getLocator()->productStorage()->client());
        };
        return $container;
    }

    protected function addCategoryStorageClient(Container $container): Container
    {
        $container[static::CLIENT_CATEGORY_STORAGE] = function (Container $container) {
            return new FirstSpiritCategoryStorageClientBridge($container->getLocator()->categoryStorage()->client());
        };
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
