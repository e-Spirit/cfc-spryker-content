<?php

namespace Crownpeak\Shared\FirstSpiritPreviewContent;

use Crownpeak\Yves\FirstSpiritPreviewContent\Exception\FirstSpiritPreviewContenentContentPageException;
use Spryker\Shared\Log\LoggerTrait;
use Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentFactory;

/**
 * Utility class to handle content pages.
 */
class FirstSpiritContentPageUtil
{
  use LoggerTrait;

  public FirstSpiritPreviewContentFactory $factory;

  public function __construct(FirstSpiritPreviewContentFactory $factory)
  {
    $this->factory = $factory;
  }

  /**
   * Renders rich texts recursivly.
   * 
   * @param $content Contents of the fs_text element of the API response.
   */
  public function getUrl(string $fsPageId, string $locale): ?string
  {
    $data = $this->getNavigationServiceEntryByPageId($fsPageId, $locale);
    $url = null;

    if (!is_null($data['customData']) && is_string($data['customData']['pageTemplate'])) {
      $fsPageLayout = $data['customData']['pageTemplate'];
      $contentPageTemplate = $this->getContentPageTemplate($fsPageLayout);

      if ($contentPageTemplate) {
        // If a template mapping is defined for this FirstSpirit layout, treat it as a content page and return a URL
        // Otherwise return no URL so frontend does not perform a redirect
        $seoRoute = $data['seoRoute'];
        $url = '/' . $this->getFactory()->getConfig()->getContentPageUrlPrefix() . $this->stripSeoRoute($seoRoute);
      }
    } else {
      $this->getLogger()->error('[ContentPageController] No custom data or no pageTemplate set for: ' . $fsPageId);
    }

    return $url;
  }


  public function getNavigationServiceEntryByPageId(string $pageId, string $locale): mixed
  {
    $navigationData = $this->getFactory()->getContentJsonFetcherClient()->fetchNavigation($locale);
    $idMap = $navigationData['idMap'];

    if (array_key_exists($pageId, $idMap)) {
      return $idMap[$pageId];
    }

    throw new FirstSpiritPreviewContenentContentPageException('Failed to find navigation service entry');
  }



  /**
   * Returns the mapped content page template to render based on the given FirstSpirit page layout.
   */
  public function getContentPageTemplate(string $fsPageLayout): ?string
  {
    $contentPageTemplateMapping = $this->getFactory()->getConfig()->getContentPageTemplateMapping();
    $contentPageTemplate = null;
    if (array_key_exists($fsPageLayout, $contentPageTemplateMapping)) {
      $contentPageTemplate = $contentPageTemplateMapping[$fsPageLayout];
    } else {
      $this->getLogger()->error('[ContentPageController] No mapping set set for layout : ' . $fsPageLayout);
    }
    return $contentPageTemplate;
  }

  /**
   * Transforms the given seoRoute as received from the Navigation Service and extracts the URL part.
   */
  public function stripSeoRoute($url): string
  {
    if (preg_match('/index\-?[\w\d]?\.json$/', $url)) {
      return preg_replace('/\/index\-?[\w\d]?\.json$/', '', $url);
    }
    $parts = explode('/', $url);
    return str_replace('.json', '', array_pop($parts));
  }

  public function getPageTitle(string $contentPageUrl, string $locale): mixed
  {
    $navigationServiceEntry = $this->getNavigationServiceEntryByUrl($contentPageUrl, $locale);

    return $navigationServiceEntry['label'];
  }

  public function getNavigationServiceEntryByUrl(string $contentPageUrl, string $locale): mixed
  {
    $navigationData = $this->getFactory()->getContentJsonFetcherClient()->fetchNavigation($locale);
    $idMap = $navigationData['idMap'];

    foreach ($idMap as $id => $pageData) {
      if ($this->matchesSeoRoute($pageData['seoRoute'], $contentPageUrl)) {
        return $pageData;
      }
    }

    throw new FirstSpiritPreviewContenentContentPageException('Failed to find navigation service entry');
  }

  public function getFirstSpiritElementFromUrl(string $contentPageUrl, string $locale): mixed
  {
    $navigationServiceEntry = $this->getNavigationServiceEntryByUrl($contentPageUrl, $locale);

    try {
      $data = $this->getFactory()->getContentJsonFetcherClient()->findElement($navigationServiceEntry['caasDocumentId'], $locale);

      return $data;
    } catch (\Throwable $th) {
      $this->getLogger()->error('[ContentPageController] Cannot get element data for: ' . $contentPageUrl . '(' . $navigationServiceEntry['caasDocumentId'] . ')');
      throw new FirstSpiritPreviewContenentContentPageException('Failed to find element data');
    }
  }

  /**
   * Checks if the given $url matches the given $seoRoute.
   */
  public function matchesSeoRoute($seoRoute, $url): string
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

  private function getFactory(): FirstSpiritPreviewContentFactory
  {
    return $this->factory;
  }
}
