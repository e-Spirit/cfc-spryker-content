<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Controller;

use Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig\FirstSpiritSectionRenderUtil;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentFactory getFactory()
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentConfig getConfig()
 */
class CmsBlockRenderController extends AbstractController
{
    use LoggerTrait;

    /**
     * @param Request $request
     *
     * @return Response
     * @throws ContainerKeyNotFoundException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
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


        $sectionRenderUtil = new FirstSpiritSectionRenderUtil(
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
