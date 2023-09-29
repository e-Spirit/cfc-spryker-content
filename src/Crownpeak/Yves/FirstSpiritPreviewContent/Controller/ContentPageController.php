<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Controller;

use Crownpeak\Yves\FirstSpiritPreviewContent\Exception\FirstSpiritPreviewContenentContentPageException;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentFactory getFactory()
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentConfig getConfig()
 */
class ContentPageController extends AbstractController
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
        $contentPageUrl = $request->get('contentPageUrl');
        $locale = $request->getLocale();

        try {
            $data = $this->getFirstSpiritElementFromUrl($contentPageUrl, $locale);

            if (!is_null($data)) {
                $this->getLogger()->error('[ContentPageController] Cannot get data for: ' . $contentPageUrl);
            }

            // TODO: Get rid of this items wrapper globally
            $this->getFactory()->getDataStore()->setCurrentPage(['items' => [$data]]);


            return $this->renderView('@FirstSpiritUi/views/fs-content-page/fs-content-page.twig', [
                'contentPageUrl' => $contentPageUrl,
                'contentPageData' => $data,
                'title' => $this->getPageTitle($contentPageUrl, $locale)
            ]);
        } catch (\Throwable $th) {
            $this->getLogger()->error('[ContentPageController] Cannot get data for: ' . $contentPageUrl);
            $this->getLogger()->error('[ContentPageController] ' . $th->getMessage());



            return $this->renderView('@FirstSpiritUi/views/fs-content-page/fs-content-page.twig', [
                'error' => 'Not found',
                'title' => 'Error'
            ]);
        }
    }


    protected function getPageTitle(string $contentPageUrl, string $locale): mixed
    {
        $navigationServiceEntry = $this->getNavigationServiceEntry($contentPageUrl, $locale);

        return $navigationServiceEntry['label'];
    }

    protected function getNavigationServiceEntry(string $contentPageUrl, string $locale): mixed
    {
        $navigationData = $this->getFactory()->getContentJsonFetcherClient()->fetchNavigation($locale);
        $idMap = $navigationData['idMap'];

        foreach ($idMap as $id => $pageData) {
            $arrayKey = strtolower('/' . $contentPageUrl . '/index.json');
            if (strtolower($pageData['seoRoute']) === $arrayKey) {
                return $pageData;
            }
        }

        throw new FirstSpiritPreviewContenentContentPageException('Failed to find navigation service entry');
    }

    protected function getFirstSpiritElementFromUrl(string $contentPageUrl, string $locale): mixed
    {
        $navigationServiceEntry = $this->getNavigationServiceEntry($contentPageUrl, $locale);

        try {
            $data = $this->getFactory()->getContentJsonFetcherClient()->findElement($navigationServiceEntry['caasDocumentId'], $locale);

            return $data;
        } catch (\Throwable $th) {
            $this->getLogger()->error('[ContentPageController] Cannot get element data for: ' . $contentPageUrl . '(' . $navigationServiceEntry['caasDocumentId'] . ')');
            throw new FirstSpiritPreviewContenentContentPageException('Failed to find element data');
        }
    }
}
