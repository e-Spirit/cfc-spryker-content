<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Controller;

use Crownpeak\Shared\FirstSpiritPreviewContent\FirstSpiritPreviewContentConstants;
use Crownpeak\Yves\FirstSpiritPreviewContent\Exception\FirstSpiritPreviewContenentContentPageException;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentFactory getFactory()
 */
class ContentPageController extends AbstractController
{
    use LoggerTrait;

    /**
     * Renders the given content page.
     * Returns HTML format.
     *
     * @param Request $request
     *
     * @return Response
     * @throws ContainerKeyNotFoundException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderAction(Request $request): Response
    {
        $contentPageUrl = $request->get('contentPageUrl');
        $locale = $request->getLocale();
        $this->getLogger()->debug('[ContentPageController] Received request: ' . $contentPageUrl);

        try {
            $data = $this->getFirstSpiritElementFromUrl($contentPageUrl, $locale);

            if (is_null($data)) {
                $this->getLogger()->error('[ContentPageController] Cannot get data for: ' . $contentPageUrl);
                return $this->renderError('Page not found.');
            }

            // TODO: Get rid of this items wrapper globally (see CFCSPRY-71)
            $this->getFactory()->getDataStore()->setCurrentPage(['items' => [$data]]);

            $fsPageLayout = $data['layout'];
            $contentPageTemplate = null;

            if (is_null($fsPageLayout)) {
                $this->getLogger()->error('[ContentPageController] No layout set for: ' . $contentPageUrl);
                return $this->renderError('Cannot render page.');
            }
            $contentPageTemplate = $this->getContentPageTemplate($fsPageLayout);
            return $this->renderView($contentPageTemplate, [
                'contentPageUrl' => $contentPageUrl,
                'contentPageData' => $data,
                'title' => $this->getPageTitle($contentPageUrl, $locale)
            ]);
        } catch (FirstSpiritPreviewContenentContentPageException $e) {
            $this->getLogger()->error('[ContentPageController] Cannot find page: ' . $contentPageUrl);
            $this->getLogger()->error('[ContentPageController] ' . $e->getMessage());

            return $this->renderError('Cannot find page.');
        } catch (\Throwable $th) {
            $this->getLogger()->error('[ContentPageController] Cannot render: ' . $contentPageUrl);
            $this->getLogger()->error('[ContentPageController] ' . $th->getMessage());

            return $this->renderError('Cannot render page: ' . $th->getMessage());
        }
    }

    protected function renderError(string $message, string $title = 'Error')
    {
        $contentPageTemplateMapping = $this->getFactory()->getConfig()->getContentPageTemplateMapping();

        if (!array_key_exists(FirstSpiritPreviewContentConstants::FIRSTSPIRIT_CONTENT_PAGE_TEMPLATE_MAPPING_ERROR, $contentPageTemplateMapping)) {
            $this->getLogger()->error('[ContentPageController] No error template defined');
            return;
        }
        return $this->renderView($contentPageTemplateMapping[FirstSpiritPreviewContentConstants::FIRSTSPIRIT_CONTENT_PAGE_TEMPLATE_MAPPING_ERROR], [
            'error' => $message,
            'title' => $title
        ]);
    }

    /**
     * Returns the URL based on the given FirstSpirit ID.
     * Returns JSON format.
     *
     * @param Request $request
     *
     * @return Response
     * @throws ContainerKeyNotFoundException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getUrlAction(Request $request): Response
    {

        $fsPageId = $request->get('pageId');
        $locale = $request->get('locale');

        if (!is_string($fsPageId)) {
            $this->getLogger()->warning('[ContentPageController] Malformatted fsPageId received');
            return $this->jsonResponse([
                'error' => 'Malformatted fsPageId received'
            ], 400);
        }

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

        return $this->jsonResponse([
            'url' => $url
        ]);
    }

    /**
     * Returns the mapped content page template to render based on the given FirstSpirit page layout.
     */
    protected function getContentPageTemplate(string $fsPageLayout): ?string
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

    protected function getPageTitle(string $contentPageUrl, string $locale): mixed
    {
        $navigationServiceEntry = $this->getNavigationServiceEntryByUrl($contentPageUrl, $locale);

        return $navigationServiceEntry['label'];
    }



    protected function getNavigationServiceEntryByPageId(string $pageId, string $locale): mixed
    {
        $navigationData = $this->getFactory()->getContentJsonFetcherClient()->fetchNavigation($locale);
        $idMap = $navigationData['idMap'];

        if (array_key_exists($pageId, $idMap)) {
            return $idMap[$pageId];
        }

        throw new FirstSpiritPreviewContenentContentPageException('Failed to find navigation service entry');
    }

    protected function getNavigationServiceEntryByUrl(string $contentPageUrl, string $locale): mixed
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

    protected function getFirstSpiritElementFromUrl(string $contentPageUrl, string $locale): mixed
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
     * Transforms the given seoRoute as received from the Navigation Service and extracts the URL part.
     */
    protected function stripSeoRoute($url): string
    {
        if (preg_match('/index\-?[\w\d]?\.json$/', $url)) {
            return preg_replace('/\/index\-?[\w\d]?\.json$/', '', $url);
        }
        $parts = explode('/', $url);
        return str_replace('.json', '', array_pop($parts));
    }

    /**
     * Checks if the given $url matches the given $seoRoute.
     */
    protected function matchesSeoRoute($seoRoute, $url): string
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
}
