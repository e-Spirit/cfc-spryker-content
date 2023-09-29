<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Route;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\Route;
use Spryker\Yves\Router\Route\RouteCollection;

/**
 * Route Plugin to enable content pages route.
 */
class FirstSpiritPreviewContentContentPagesRoutePlugin extends AbstractRouteProviderPlugin
{
    protected const FIRST_SPIRIT_PREVIEW_MODULE_BUNDLE = 'FirstSpiritPreviewContent';

    protected const FS_CONTENT_PAGE_CONTROLLER = 'ContentPage';
    protected const CONTROLLER_INDEX_ACTION = 'index';

    protected const PATH_VARIABLE = 'content';
    protected const PATH_VALUE = 'content';

    protected const CONTENT_PAGE_URL_PATTERN = '[\\w\\-_\\d]+';



    /**
     * @param RouteCollection $routeCollection
     * @return RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {

        $routeCollection->add(self::PATH_VARIABLE, $this->buildCmsBlockRenderRoute());

        return $routeCollection;
    }

    /**
     * @return Route
     */
    protected function buildCmsBlockRenderRoute(): Route
    {
        $path = '/{' . self::PATH_VARIABLE . '}/{contentPageUrl}';
        $route = $this->buildRoute($path, self::FIRST_SPIRIT_PREVIEW_MODULE_BUNDLE, self::FS_CONTENT_PAGE_CONTROLLER, self::CONTROLLER_INDEX_ACTION);
        $route->setRequirement('contentPageUrl', static::CONTENT_PAGE_URL_PATTERN);
        $route->setRequirement(self::PATH_VARIABLE, self::PATH_VALUE);
        $route->setMethods('GET');

        return $route;
    }
}
