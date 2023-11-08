<?php

namespace Crownpeak\Yves\FirstSpiritContent\Controller;

use Crownpeak\Yves\FirstSpiritContent\Plugin\Twig\SectionRenderUtil;
use Spryker\Shared\Log\LoggerTrait;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Controller to handle the CMS block render route.
 * Responds to the request with the rendered representation of the requested section.
 * 
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentFactory getFactory()
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentConfig getConfig()
 */
class CmsBlockRenderController extends AbstractController
{
    use LoggerTrait;

    /**
     * Default action to render a given section based on the parameters passed by the request.
     * Used to partially re-render only the changed content in preview mode.
     * 
     * @param Request $request The request that triggered the action.
     * @return Response The response to pass to the client.
     */
    public function indexAction(Request $request): Response
    {
        if (!$this->getFactory()->getPreviewService()->isPreview()) {
            $this->getLogger()->warning('[CmsBlockRenderController] Tried to access partial rendering while not in preview');
            return $this->jsonResponse([
                'error' => 'Unauthorized'
            ], 401);
        }

        $fsPageId = $request->get('fsPageId');
        $sectionId = $request->get('sectionId');
        $wrapRequired = strtolower($request->get('wrap', '')) === 'true';
        $locale = $request->get('locale');
        if (!is_string($fsPageId)) {
            $this->getLogger()->warning('[CmsBlockRenderController] Malformatted fsPageId received');
            return $this->jsonResponse([
                'error' => 'Malformatted fsPageId received'
            ], 400);
        }
        if (!is_string($sectionId)) {
            $this->getLogger()->warning('[CmsBlockRenderController] Malformatted sectionId received');
            return $this->jsonResponse([
                'error' => 'Malformatted sectionId received'
            ], 400);
        }


        $sectionRenderUtil = new SectionRenderUtil(
            $this->getTwig(),
            $this->getFactory()
        );

        // No attempt to cache as this is only used in preview mode
        try {
            $data = $this->getFactory()->getContentJsonFetcherClient()->findElement($fsPageId, $locale);

            if (is_null($data)) {
                $this->getLogger()->error('[CmsBlockRenderController] Cannot find page: ' . $fsPageId);
                return $this->jsonResponse([
                    'error' => 'Page not found'
                ], 400);
            }

            $sectionToRender = null;

            foreach ($data['children'] as $slot) {
                foreach ($slot['children'] as $section) {
                    if ($section['id'] === $sectionId) {
                        $sectionToRender = $section;
                    }
                }
            }

            if (is_null($sectionToRender)) {
                $this->getLogger()->error('[CmsBlockRenderController] Cannot find section: ' . $sectionId . ' in page: ' . $fsPageId);
                return $this->jsonResponse([
                    'error' => 'Section not found'
                ], 400);
            }

            $renderResult = $sectionRenderUtil->renderSection($sectionToRender);

            if ($wrapRequired) {
                $renderResult = $sectionRenderUtil->decorateSection($renderResult, $sectionId . '.' . $locale);
            }

            return $this->jsonResponse([
                'renderResult' => $renderResult
            ]);
        } catch (\Throwable $th) {
            $this->getLogger()->error('[CmsBlockRenderController] Cannot get data for: ' . $fsPageId);
            $this->getLogger()->error('[CmsBlockRenderController] ' . $th->getMessage());


            return $this->jsonResponse([
                'error' => $th->getMessage()
            ], 400);
        }
    }
}
