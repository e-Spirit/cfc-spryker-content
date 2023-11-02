<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig;

use Crownpeak\Yves\FirstSpiritPreviewContent\Exception\FirstSpiritPreviewContentTemplateException;
use Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentConfig;
use Spryker\Shared\Log\LoggerTrait;
use Twig\Environment;

/**
 * Utility class to render rich text elements.
 */
class FirstSpiritRichTextUtil
{
  use LoggerTrait;

  private Environment $twig;
  private FirstSpiritPreviewContentConfig $config;

  public function __construct(Environment $twig, FirstSpiritPreviewContentConfig $config)
  {
    $this->twig = $twig;
    $this->config = $config;
  }

  /**
   * Renders rich texts recursivly.
   * 
   * @param $content Contents of the fs_text element of the API response.
   */
  public function renderRichText(mixed $content): string
  {
    if (empty($content)) return '';
    if (is_string($content)) return $content;

    return implode('', array_map(function ($element) {
      $type = $element['type'];
      $content = $element['content'];
      $data = $element['data'];

      switch ($type) {
        case 'block':
          return '<div>' . $this->renderWithFormat($data, $content) . '</div>';
        case 'linebreak':
          return '<br>';
        case 'paragraph':
          return '<p>' . $this->renderWithFormat($data, $content) . '</p>';
        case 'text':
          return $this->renderWithFormat($data, $content);
        case 'link':
          return $this->renderLinkWithFormat($data, $content);
        case 'list':
          return '<ul style="list-style: disc; margin-left: 20px;">'
            . $this->renderRichText($content) . '</ul>';
        case 'listitem':
          return '<li>' . $this->renderWithFormat($data, $content) . '</li>';
        default:
          return '';
      }
    }, $content));
  }

  private function renderWithFormat($data, $content): string
  {

    $formatName = null;

    if (isset($data['format'])) {
      // Default formatting
      $formatName = $data['format'];
    } else if (isset($data['data-fs-style'])) {
      // Custom formatting
      $formatName = $data['data-fs-style'];
    }

    if (is_null($formatName)) {
      $this->getLogger()->debug('[FirstSpiritRichTextUtil] No template name found for ' . json_encode($content));
      return $this->renderRichText($content);
    }

    return $this->render($formatName, $data, $content);
  }


  private function renderLinkWithFormat($data, $content): string
  {

    $formatName = null;

    if (isset($data['template'])) {
      $formatName = $data['template'];
    }

    if (is_null($formatName)) {
      $this->getLogger()->debug('[FirstSpiritRichTextUtil] No template name found for ' . json_encode($content));
      return $this->renderRichText($content);
    }

    return $this->render($formatName, $data, $content);
  }


  private function render($formatName, $data, $content): string
  {
    $fullTemplate = '';

    if (array_key_exists($formatName, $this->config->getDomEditorTemplateMapping())) {
      $fullTemplate = $this->config->getDomEditorTemplateMapping()[$formatName];
    } else {
      $this->getLogger()->warning('[FirstSpiritRichTextUtil] No mapping found for ' . $formatName);

      if ($this->getConfig()->shouldDisplayBlockRenderErrors()) {
        throw new FirstSpiritPreviewContentTemplateException('No mapping found for ' . $formatName);
      } else {
        return '';
      }
    }

    try {
      $splitTemplate = explode('/', $fullTemplate);
      if (count($splitTemplate) !== 2) {
        throw new FirstSpiritPreviewContentTemplateException('Invalid template path ' . $fullTemplate);
      }
      $templateModule = $splitTemplate[0];
      $template = $splitTemplate[1];
      $this->getLogger()->debug('[FirstSpiritRichTextUtil] Attempting to render link format ' . $formatName . ' with template ' . $template);
      $renderedBlock = $this->twig->render('@CmsBlock/template/fs_content_block.twig', [
        'fsData' => [
          'data' => $data,
          'content' => $content
        ],
        'template' => $template,
        'templateModule' => $templateModule
      ]);
      return $renderedBlock;
    } catch (\Throwable $th) {
      $this->getLogger()->error('[FirstSpiritRichTextUtil] Error during rendering of section ' . $formatName);
      $this->getLogger()->error(sprintf(
        '[FirstSpiritRichTextUtil] %s\n%s',
        $th->getMessage(),
        $th->getTraceAsString()
      ));


      if ($this->getConfig()->shouldDisplayBlockRenderErrors()) {
        // If errors should be displayed, re-throw so error page with details is displayed
        throw $th;
      }
    }
  }

  private function getConfig()
  {
    return $this->config;
  }
}
