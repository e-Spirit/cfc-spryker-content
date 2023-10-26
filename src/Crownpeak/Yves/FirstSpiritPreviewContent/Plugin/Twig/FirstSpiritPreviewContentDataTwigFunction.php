<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Twig\TwigFunction;
use Twig\Environment;


/**
 * Twig function go set Content Url and get content data.
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentFactory getFactory()
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentConfig getConfig()
 */
class FirstSpiritPreviewContentDataTwigFunction extends AbstractPlugin implements TwigPluginInterface
{
    use LoggerTrait;

    private Environment $twig;

    /**
     * This is the name of the global function that will be available in the twig templates.
     * usage: {{ firstSpiritContent(slotName) }}
     * @var string
     */
    protected const FIRSTSPIRIT_CONTENT = 'firstSpiritContent';

    /**
     * This is the name of the global function that will be available in the twig templates.
     * usage: {{ firstSpiritContent(slotName) }}
     * @var string
     */
    protected const FIRSTSPIRIT_RICHT_TEXT = 'firstSpiritRichText';

    /**
     * This is the name of the global function that will be available in the twig templates.
     * usage: {{ firstSpiritContentLink(pageId, previewId) }}
     * @var string
     */
    protected const FIRSTSPIRIT_CONTENT_LINK = 'firstSpiritContentLink';


    protected FirstSpiritSectionRenderUtil $sectionRenderUtil;

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
        $this->sectionRenderUtil = new FirstSpiritSectionRenderUtil(
            $twig,
            $this->getFactory(),
            $this->getConfig()
        );

        $this->twig = $twig;
        $twig->addFunction(
            new TwigFunction(
                static::FIRSTSPIRIT_CONTENT,
                [$this, 'firstSpiritContent']
            )
        );

        $twig->addFunction(
            new TwigFunction(
                static::FIRSTSPIRIT_RICHT_TEXT,
                [$this, 'firstSpiritRichText']
            )
        );

        $twig->addFunction(
            new TwigFunction(
                static::FIRSTSPIRIT_CONTENT_LINK,
                [$this, 'firstSpiritContentLink']
            )
        );

        return $twig;
    }

    /**
     * Returns the rendered content for the slot with the given name.
     * Accesses the data based on what has been fetched earlier using FirstSpiritAttributesTwigFunction.
     *
     * @param string $slotName The name of the slot to render.
     * @return string The content to be inserted into the DOM.
     */
    public function firstSpiritContent($slotName): string
    {
        $this->getLogger()->info('[FirstSpiritPreviewContentDataTwigFunction] Getting data for slot: ' . $slotName);

        $data = $this->getFactory()->getDataStore()->getCurrentPage();
        if (is_null($data)) {
            $error = $this->getFactory()->getDataStore()->getError();
            if (!is_null($error)) {
                $this->getLogger()->info('[FirstSpiritPreviewContentDataTwigFunction] Rendering error for slot: ' . $slotName);
                return $this->sectionRenderUtil->decorateSlot($this->sectionRenderUtil->getErrorMessage($error), $slotName);
            }
        }

        if (empty($data) || count($data['items']) === 0) {
            $this->getLogger()->info('[FirstSpiritPreviewContentDataTwigFunction] No items found');
            return $this->sectionRenderUtil->decorateSlot('', $slotName);
        }
        $pageContent = $data['items'][0];
        $slotContent = NULL;

        foreach ($pageContent['children'] as $slot) {
            if ($slot['name'] === $slotName) {
                $slotContent = $slot;
                break;
            }
        }


        if (is_null($slotContent)) {
            $this->getLogger()->warning('[FirstSpiritPreviewContentDataTwigFunction] Slot not found: ' . $slotName);
            return '';
        }


        $renderedContent = '';

        $this->getLogger()->info('[FirstSpiritPreviewContentDataTwigFunction] Found ' . count($slotContent['children']) . ' sections to render');

        foreach ($slotContent['children'] as $section) {
            $renderedBlock = $this->sectionRenderUtil->renderSection($section);
            $renderedContent .= $this->sectionRenderUtil->decorateSection($renderedBlock, $section['previewId']);
        }
        return $this->sectionRenderUtil->decorateSlot($renderedContent, $slotName);
    }


    /**
     * Render rich text with format.
     *
     * @param mixed $content The content as received from the API.
     * @return string
     */
    public function firstSpiritRichText($content): string
    {
        $richTextUtil = new FirstSpiritRichTextUtil($this->twig, $this->getConfig());
        return $richTextUtil->renderRichText($content);
    }

    /**
     * Get URL of content link.
     *
     * @param string $pageId Page identifier of the referenced content page.
     * @param string $previewId Preview identifier of the section.
     * @return mixed URL of the content link.
     */
    public function firstSpiritContentLink($pageId, $previewId): mixed
    {
        // Determine origin of application
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $origin = $protocol . '://' . $host;

        // Extract the locale from the previewId
        $substring = explode('.', $previewId);
        $locale = end($substring);

        // Build the request with the required parameters
        $params = http_build_query([
          'pageId' => $pageId,
          'locale' => $locale,
        ]);
        $request = $origin . '/getContentPageUrl?' . $params;

        // Initialize cURL session
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }
}
