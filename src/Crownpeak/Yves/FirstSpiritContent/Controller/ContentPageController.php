<?php

namespace Crownpeak\Yves\FirstSpiritContent\Controller;

use Crownpeak\Shared\FirstSpiritContent\ContentPageUtil;
use Crownpeak\Shared\FirstSpiritContent\FirstSpiritContentConstants;
use Crownpeak\Yves\FirstSpiritContent\Exception\ContentPageException;
use Spryker\Shared\Log\LoggerTrait;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Controller to handle the content page routes.
 * 
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentFactory getFactory()
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentConfig getConfig()
 */
class ContentPageController extends AbstractController
{
    use LoggerTrait;

    private ContentPageUtil $contentPageUtil;

    public function __construct()
    {
        $this->contentPageUtil = new ContentPageUtil($this->getFactory());
    }

    /**
     * Action to render a given content page based on the parameters passed by the request.
     * Returns HTML format.
     * 
     * @param Request $request The request that triggered the action.
     * @return Response The response to pass to the client.
     */
    public function renderAction(Request $request): Response
    {
        $contentPageUrl = $request->get('contentPageUrl');
        $locale = $request->getLocale();
        $this->getLogger()->debug('[ContentPageController] Received request: ' . $contentPageUrl);

        try {
            $data = $this->contentPageUtil->getFirstSpiritElementFromUrl($contentPageUrl, $locale);

            if (is_null($data)) {
                $this->getLogger()->error('[ContentPageController] Cannot get data for: ' . $contentPageUrl);
                return $this->renderError('Page not found.');
            }

            $this->getFactory()->getDataStore()->setCurrentPage($data);

            $fsPageLayout = $data['layout'];
            $contentPageTemplate = null;

            if (is_null($fsPageLayout)) {
                $this->getLogger()->error('[ContentPageController] No layout set for: ' . $contentPageUrl);
                return $this->renderError('Cannot render page.');
            }
            $contentPageTemplate = $this->contentPageUtil->getContentPageTemplate($fsPageLayout);
            return $this->renderView($contentPageTemplate, [
                'contentPageUrl' => $contentPageUrl,
                'contentPageData' => $data,
                'title' => $this->contentPageUtil->getPageTitle($contentPageUrl, $locale)
            ]);
        } catch (ContentPageException $e) {
            $this->getLogger()->error('[ContentPageController] Cannot find page: ' . $contentPageUrl);
            $this->getLogger()->error('[ContentPageController] ' . $e->getMessage());

            return $this->renderError('Cannot find page.');
        } catch (\Throwable $th) {
            $this->getLogger()->error('[ContentPageController] Cannot render: ' . $contentPageUrl);
            $this->getLogger()->error('[ContentPageController] ' . $th->getMessage());

            return $this->renderError('Cannot render page: ' . $th->getMessage());
        }
    }

    /**
     * Action to return the URL based on the given FirstSpirit ID.
     * Returns JSON format.
     * 
     * @param Request $request The request that triggered the action.
     * @return Response The response to pass to the client.
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

        $url = $this->contentPageUtil->getUrl($fsPageId, $locale);

        return $this->jsonResponse([
            'url' => $url
        ]);
    }

    /**
     * Renders the given error.
     * 
     * @param string $message Message of the error.
     * @param string $title Title of the error
     * @return Response The response to pass to the client.
     */
    private function renderError(string $message, string $title = 'Error'): ?Response
    {
        $contentPageTemplateMapping = $this->getFactory()->getConfig()->getContentPageTemplateMapping();

        if (!array_key_exists(FirstSpiritContentConstants::FIRSTSPIRIT_CONTENT_PAGE_TEMPLATE_MAPPING_ERROR, $contentPageTemplateMapping)) {
            $this->getLogger()->error('[ContentPageController] No error template defined');
            return null;
        }
        return $this->renderView($contentPageTemplateMapping[FirstSpiritContentConstants::FIRSTSPIRIT_CONTENT_PAGE_TEMPLATE_MAPPING_ERROR], [
            'error' => $message,
            'title' => $title
        ]);
    }
}
