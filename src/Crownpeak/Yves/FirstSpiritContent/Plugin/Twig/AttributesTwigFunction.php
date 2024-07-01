<?php

namespace Crownpeak\Yves\FirstSpiritContent\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Twig\TwigFunction;
use Twig\Environment;


/**
 * Twig function to build attributes to use in the body tag in HTML.
 * Saves the information of the current page to the DataStore so it can be used later.
 *
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentFactory getFactory()
 */
class AttributesTwigFunction extends AbstractPlugin implements TwigPluginInterface
{
    use LoggerTrait;

    private Environment $twig;

    /**
     * This is the name of the global function that will be available in the twig templates.
     * usage: {{ firstSpiritAttributes(id, type, template, title, locale [,isFsDriven]) }}
     */
    protected const FIRSTSPIRIT_CFC_ATTRIBUTES_SCRIPT_DATA = 'firstSpiritAttributes';

    /**
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $this->twig = $twig;
        $twig->addFunction(
            new TwigFunction(
                static::FIRSTSPIRIT_CFC_ATTRIBUTES_SCRIPT_DATA,
                [$this, 'firstSpiritAttributes']
            )
        );

        return $twig;
    }

    /**
     * The data that will be queried and added to the twig template(s).
     * Returns an empty string if not in preview mode.
     *
     * @param $id ID of the page.
     * @param $type Type of the page.
     * @param $template Template of the page.
     * @param $title Title of the page.
     * @param $locale Locale of the page.
     * @param $isFsDriven Whether the page is FirstSpriti driven, default is false.
     * @return string The Attributes to add to the body tag.
     */
    public function firstSpiritAttributes($id, $type, $template, $title, $locale, $isFsDriven = false): string
    {
        $isPreview = $this->getFactory()->getPreviewService()->isPreview();

        $cacheKey = md5($id . $type . $locale . ($isPreview ? 'preview' : 'release'));

        $this->getLogger()->debug('[AttributesTwigFunction] Setting attributes for: ' . $type . ' ' . $id . ' (Preview=' . $isPreview . ')');

        $data = null;
        if (!$isPreview && $this->getFactory()->getStorageClient()->hasApiResponse($cacheKey)) {
            // Check for entry in cache
            $data = $this->getFactory()->getStorageClient()->getApiResponse($cacheKey);
        } else {
            // If not in cache or in preview mode, query
            try {
                if ($isFsDriven) { // For FS driven pages use findElement
                    $data = $this->getFactory()->getContentJsonFetcherClient()->findElement($id, $locale);
                } else { // For shop driven pages use findPage
                    $data = $this->getFactory()->getContentJsonFetcherClient()->findPage($id, $type, $locale);
                }
                if (!empty($data) && !is_null($data)) {
                    $this->getFactory()->getStorageClient()->setApiResponse($cacheKey, $data);
                }
            } catch (\Throwable $th) {
                $this->getLogger()->error('[AttributesTwigFunction] Cannot get data for: ' . $type . ' ' . $id . ' (Preview=' . $isPreview . ')');
                $this->getLogger()->error('[AttributesTwigFunction] ' . $th->getMessage());
                $this->getFactory()->getDataStore()->setCurrentPage(null);
                $this->getFactory()->getDataStore()->setError($th);
                return '';
            }
        }

        $previewId = null;
        if (empty($data) || is_null($data)) {
            $this->getLogger()->info('[AttributesTwigFunction] No element found for: ' . $type . ' ' . $id);
        } else {
            $pageContent = $data;

            $previewId = $pageContent['previewId'];

            if (!isset($previewId)) {
                $this->getLogger()->error('[AttributesTwigFunction] No preview ID found');
            } else {
                // If data is found, save it to factory to access it in other Twig functions later
                $this->getFactory()->getDataStore()->setCurrentPage($data);
            }
        }

        // Only print attributes when in preview mode as they are only required by FE API in client
        if (!$isPreview) {
            return '';
        }

        return printf(
            'data-fs-preview-id="%s" data-is-fs-driven="%s" data-fs-page-id="%s" data-fs-page-type="%s" data-fs-page-template="%s" data-fs-name-%s="%s" data-fs-lang="%s"',
            $previewId,
            $isFsDriven ? 'true' : 'false',
            $id,
            $type,
            $template,
            $locale,
            $title,
            $locale
        );
    }
}
