<?php

namespace Crownpeak\Shared\FirstSpiritContent;

use Crownpeak\Yves\FirstSpiritContent\Exception\ContentPageException;
use Spryker\Shared\Log\LoggerTrait;
use Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentFactory;

/**
 * Utility class to handle content pages.
 */
class ContentPageUtil
{
    use LoggerTrait;

    public FirstSpiritContentFactory $factory;

    public function __construct(FirstSpiritContentFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Returns the URL of the page with the given ID.
     * 
     * @param string $fsPageId The ID of the page.
     * @param string $locale The locale to pass.
     * @return ?string The URL of the given page if valid, null otherwise.
     */
    public function getUrl(string $fsPageId, string $locale): ?string
    {
        $data = $this->getNavigationServiceEntryByPageId($fsPageId, $locale);
        $url = null;

        if (!is_null($data['customData']) && is_string($data['customData']['pageTemplate'])) {
            // Check for homepage
            if (array_key_exists('ecomShopId', $data['customData']) &&  $data['customData']['ecomShopId'] === 'homepage') {
                return '/';
            }
            $fsPageLayout = $data['customData']['pageTemplate'];
            $contentPageTemplate = $this->getContentPageTemplate($fsPageLayout);

            if ($contentPageTemplate) {
                // If a template mapping is defined for this FirstSpirit layout, treat it as a content page and return a URL
                // Otherwise return no URL so frontend does not perform a redirect
                $seoRoute = $data['seoRoute'];
                $url = '/' . $this->getFactory()->getConfig()->getContentPageUrlPrefix() . $this->stripSeoRoute($seoRoute);
            }
        } else {
            $this->getLogger()->error('[ContentPageUtil] No custom data or no pageTemplate set for: ' . $fsPageId);
        }

        return $url;
    }


    /**
     * Returns the mapped content page template to render based on the given FirstSpirit page layout.
     * 
     * @param string $fsPageLayout The layout of the FS page.
     * @return ?string The mapped template. Null if no mapping is present.
     */
    public function getContentPageTemplate(string $fsPageLayout): ?string
    {
        $contentPageTemplateMapping = $this->getFactory()->getConfig()->getContentPageTemplateMapping();
        $contentPageTemplate = null;
        if (array_key_exists($fsPageLayout, $contentPageTemplateMapping)) {
            $contentPageTemplate = $contentPageTemplateMapping[$fsPageLayout];
        } else {
            $this->getLogger()->error('[ContentPageUtil] No mapping set set for layout : ' . $fsPageLayout);
        }
        return $contentPageTemplate;
    }

    /**
     * Returns the page title for the page with the given URL.
     * 
     * @param string $contentPageUrl The URL of the page to get the title for.
     * @param string $locale The locale to pass.
     * @return ?string The title of the page.
     */
    public function getPageTitle(string $contentPageUrl, string $locale): ?string
    {
        $navigationServiceEntry = $this->getNavigationServiceEntryByUrl($contentPageUrl, $locale);

        return $navigationServiceEntry['label'];
    }

    /**
     * Returns the findElement response for the FS page with the given URL.
     * 
     * @param string $contentPageUrl The URL to get the entry for.
     * @param string $locale The locale to pass.
     * @return array The findElement response for the given URL.
     */
    public function getFirstSpiritElementFromUrl(string $contentPageUrl, string $locale): mixed
    {
        $navigationServiceEntry = $this->getNavigationServiceEntryByUrl($contentPageUrl, $locale);

        try {
            $data = $this->getFactory()->getContentJsonFetcherClient()->findElement($navigationServiceEntry['caasDocumentId'], $locale);

            return $data;
        } catch (\Throwable $th) {
            $this->getLogger()->error('[ContentPageUtil] Cannot get element data for: ' . $contentPageUrl . '(' . $navigationServiceEntry['caasDocumentId'] . ')');
            throw new ContentPageException('Failed to find element data');
        }
    }

    /**
     * Returns the Navigation Service entry for the given page.
     * 
     * @param string $pageId The ID of the page.
     * @param string $locale The locale to pass.
     * @return array Information about the matching Navigation Service entry.
     */
    private function getNavigationServiceEntryByPageId(string $pageId, string $locale): array
    {
        $navigationData = $this->getFactory()->getContentJsonFetcherClient()->fetchNavigation($locale);
        $idMap = $navigationData['idMap'];

        if (array_key_exists($pageId, $idMap)) {
            return $idMap[$pageId];
        }

        throw new ContentPageException('Failed to find navigation service entry');
    }

    /**
     * Transforms the given seoRoute as received from the Navigation Service and extracts the URL part.
     * Will always have a leading slash.
     * 
     * @param string $url The URL to strip.
     * @return string The transformed URL.
     */
    private function stripSeoRoute(string $url): string
    {
        if (preg_match('/index\-?[\w\d]?\.json$/', $url)) {
            return preg_replace('/\/index\-?[\w\d]?\.json$/', '', $url);
        }
        $parts = explode('/', $url);
        $route = str_replace('.json', '', array_pop($parts));
        if (!str_starts_with($route, '/')) $route = '/' . $route;
        return $route;
    }

    /**
     * Returns the Navigation Service entry for the given URL.
     * 
     * @param string $contentPageUrl The URL to get the entry for.
     * @param string $locale The locale to pass.
     * @return array The Navigation Service entry for the given URL.
     */
    private function getNavigationServiceEntryByUrl(string $contentPageUrl, string $locale): array
    {
        $navigationData = $this->getFactory()->getContentJsonFetcherClient()->fetchNavigation($locale);
        $idMap = $navigationData['idMap'];

        foreach ($idMap as $id => $pageData) {
            if ($this->matchesSeoRoute($pageData['seoRoute'], $contentPageUrl)) {
                return $pageData;
            }
        }

        throw new ContentPageException('Failed to find navigation service entry');
    }

    /**
     * Checks if the given $url matches the given $seoRoute.
     * 
     * @param string $seoRoute The SEO route param.
     * @param string $url The URL param.
     * @return bool Whether both parameters match.
     */
    private function matchesSeoRoute(string $seoRoute, string $url): bool
    {
        $regex = '/\/' . preg_quote(strtolower($url), '/') . '\/index\-?[\w\d]?\.json$/';

        if (preg_match($regex, strtolower($seoRoute))) {
            return true;
        }

        if (str_ends_with(strtolower($seoRoute), strtolower('/' . $url . '.json'))) {
            return true;
        }

        return false;
    }

    private function getFactory(): FirstSpiritContentFactory
    {
        return $this->factory;
    }
}
