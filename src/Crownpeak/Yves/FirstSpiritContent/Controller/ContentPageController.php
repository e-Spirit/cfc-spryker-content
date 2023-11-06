<?php

namespace Crownpeak\Yves\FirstSpiritContent\Controller;

use Crownpeak\Shared\FirstSpiritContent\ContentPageUtil;
use Crownpeak\Shared\FirstSpiritContent\FirstSpiritContentConstants;
use Crownpeak\Yves\FirstSpiritContent\Exception\FirstSpiritPreviewContenentContentPageException;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentFactory getFactory()
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
            $data = $this->contentPageUtil->getFirstSpiritElementFromUrl($contentPageUrl, $locale);

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
            $contentPageTemplate = $this->contentPageUtil->getContentPageTemplate($fsPageLayout);
            return $this->renderView($contentPageTemplate, [
                'contentPageUrl' => $contentPageUrl,
                'contentPageData' => $data,
                'title' => $this->contentPageUtil->getPageTitle($contentPageUrl, $locale)
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

        if (!array_key_exists(FirstSpiritContentConstants::FIRSTSPIRIT_CONTENT_PAGE_TEMPLATE_MAPPING_ERROR, $contentPageTemplateMapping)) {
            $this->getLogger()->error('[ContentPageController] No error template defined');
            return;
        }
        return $this->renderView($contentPageTemplateMapping[FirstSpiritContentConstants::FIRSTSPIRIT_CONTENT_PAGE_TEMPLATE_MAPPING_ERROR], [
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

        $url = $this->contentPageUtil->getUrl($fsPageId, $locale);

        return $this->jsonResponse([
            'url' => $url
        ]);
    }
}
