<?php

namespace Crownpeak\Yves\FirstSpiritContent\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Twig\TwigFunction;
use Twig\Environment;


/**
 * Twig function to render FirstSpirit content.
 *
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentFactory getFactory()
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentConfig getConfig()
 */
class ContentTwigFunction extends AbstractPlugin implements TwigPluginInterface
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

    protected SectionRenderUtil $sectionRenderUtil;

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
        $this->sectionRenderUtil = new SectionRenderUtil(
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

        return $twig;
    }

    /**
     * Returns the rendered content for the slot with the given name.
     * Accesses the data based on what has been fetched earlier using AttributesTwigFunction.
     *
     * @param string $slotName The name of the slot to render.
     * @return string The content to be inserted into the DOM.
     */
    public function firstSpiritContent($slotName): string
    {
        $this->getLogger()->debug('[ContentTwigFunction] Getting data for slot: ' . $slotName);

        $data = $this->getFactory()->getDataStore()->getCurrentPage();
        if (is_null($data)) {
            $error = $this->getFactory()->getDataStore()->getError();
            if (!is_null($error)) {
                $this->getLogger()->warning('[ContentTwigFunction] Rendering error for slot: ' . $slotName);
                return $this->sectionRenderUtil->decorateSlot($this->sectionRenderUtil->getErrorMessage($error), $slotName);
            }
        }

        if (empty($data) || count($data['items']) === 0) {
            $this->getLogger()->info('[ContentTwigFunction] No items found');
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
            $this->getLogger()->warning('[ContentTwigFunction] Slot not found: ' . $slotName);
            return '';
        }


        $renderedContent = '';

        $this->getLogger()->debug('[ContentTwigFunction] Found ' . count($slotContent['children']) . ' sections to render');

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
     * @return string The rendered rich text in HTML.
     */
    public function firstSpiritRichText($content): string
    {
        $richTextUtil = new RichTextUtil($this->twig, $this->getConfig());
        return $richTextUtil->renderRichText($content);
    }
}
