<?php

namespace Crownpeak\Yves\FirstSpiritContent\Plugin\Route;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\Route;
use Spryker\Yves\Router\Route\RouteCollection;

/**
 * Route Plugin to enable partial rendering.
 */
class CmsBlockRenderRoutePlugin extends AbstractRouteProviderPlugin
{
    protected const FIRST_SPIRIT_PREVIEW_MODULE_BUNDLE = 'FirstSpiritContent';

    protected const FS_CMS_BLOCK_RENDER_CONTROLLER = 'CmsBlockRender';
    protected const CONTROLLER_INDEX_ACTION = 'index';

    protected const ROUTE_CMS_BLOCK_RENDER = 'cms-block-render';
    protected const PATH_VARIABLE = 'fsPreview';
    protected const PATH_VALUE = 'fs-preview';
    protected const PATH_ASSERTION = 'fs-preview|fs-preview';



    /**
     * @param RouteCollection $routeCollection
     * @return RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {

        $routeCollection->add(self::ROUTE_CMS_BLOCK_RENDER, $this->buildCmsBlockRenderRoute());

        return $routeCollection;
    }

    /**
     * Builds the route to handle partial rendering.
     * 
     * @return Route The route created.
     */
    protected function buildCmsBlockRenderRoute(): Route
    {
        $path = '/{' . self::PATH_VARIABLE . '}/cms-block-render';
        $route = $this->buildRoute($path, self::FIRST_SPIRIT_PREVIEW_MODULE_BUNDLE, self::FS_CMS_BLOCK_RENDER_CONTROLLER, self::CONTROLLER_INDEX_ACTION);
        $route->setRequirement(self::PATH_VARIABLE, self::PATH_VALUE);
        $route->setMethods('GET');
        return $route;
    }
}
