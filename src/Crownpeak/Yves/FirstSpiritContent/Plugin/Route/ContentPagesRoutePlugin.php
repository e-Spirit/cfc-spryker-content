<?php

namespace Crownpeak\Yves\FirstSpiritContent\Plugin\Route;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\Route;
use Spryker\Yves\Router\Route\RouteCollection;

/**
 * Route Plugin to enable content pages route.
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentConfig getConfig()
 */
class ContentPagesRoutePlugin extends AbstractRouteProviderPlugin
{
    protected const FIRST_SPIRIT_PREVIEW_MODULE_BUNDLE = 'FirstSpiritContent';
    protected const FS_CONTENT_PAGE_CONTROLLER = 'ContentPage';

    protected const CONTENT_PAGE_RENDER_ACTION = 'render';
    protected const CONTENT_PAGE_RENDER_PATH_VARIABLE = 'content';
    protected const CONTENT_PAGE_RENDER_URL_PATTERN = '[\\w\\-_\\d\\/]+';

    protected const CONTENT_PAGE_GET_URL_ACTION = 'getUrl';
    protected const CONTENT_PAGE_GET_URL_PATH_VARIABLE = 'getContentPageUrl';


    /**
     * @param RouteCollection $routeCollection
     * @return RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {

        $routeCollection->add($this->getConfig()->getContentPageUrlPrefix(), $this->buildContentPageRenderRoute());
        $routeCollection->add(self::CONTENT_PAGE_GET_URL_PATH_VARIABLE, $this->buildContentPageGetUrlRoute());

        return $routeCollection;
    }

    /**
     * @return Route
     */
    protected function buildContentPageRenderRoute(): Route
    {
        $path = '/' . $this->getConfig()->getContentPageUrlPrefix() . '/{contentPageUrl}';
        $route = $this->buildRoute($path, self::FIRST_SPIRIT_PREVIEW_MODULE_BUNDLE, self::FS_CONTENT_PAGE_CONTROLLER, self::CONTENT_PAGE_RENDER_ACTION);
        $route->setRequirement('contentPageUrl', static::CONTENT_PAGE_RENDER_URL_PATTERN);
        $route->setMethods('GET');

        return $route;
    }
    /**
     * @return Route
     */
    protected function buildContentPageGetUrlRoute(): Route
    {
        $path = '/' . self::CONTENT_PAGE_GET_URL_PATH_VARIABLE;
        $route = $this->buildRoute($path, self::FIRST_SPIRIT_PREVIEW_MODULE_BUNDLE, self::FS_CONTENT_PAGE_CONTROLLER, self::CONTENT_PAGE_GET_URL_ACTION);
        $route->setMethods('GET');

        return $route;
    }
}
