<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig;

use Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentFactory;

/**
 * Utility class to render rich text elements.
 */
class FirstSpiritRichTextUtil
{
  private array $renderUtilPerLinkTemplateMap;
  private array $renderUtilPerFormatTemplateMap;

  public function __construct(FirstSpiritPreviewContentFactory $factory, string $locale, array $renderUtils)
  {
    $this->renderUtilPerLinkTemplateMap = [];
    $this->renderUtilPerFormatTemplateMap = [];

    foreach ($renderUtils as $key => $utilClass) {
      $util = new $utilClass($factory, $locale);
      $supportedLinkFormats = $util->getSupportedLinkTemplates();
      if (is_array($supportedLinkFormats)) {
        foreach ($supportedLinkFormats as $key2 => $format) {
          $this->renderUtilPerLinkTemplateMap[$format] = $util;
        }
      }
      $supportedTemplateFormats = $util->getSupportedFormatTemplates();
      if (is_array($supportedTemplateFormats)) {
        foreach ($supportedTemplateFormats as $key2 => $format) {
          $this->renderUtilPerFormatTemplateMap[$format] = $util;
        }
      }
    }
  }

  /**
   * Renders rich texts recursivly.
   * 
   * @param $content Contents of the fs_text element of the API response.
   */
  public function renderRichText(array $content): string
  {
    if (empty($content)) return '';
    if (is_string($content)) return $content;

    return implode('', array_map(function ($element) {
      $type = $element['type'];
      $content = $element['content'];
      $data = $element['data'];

      switch ($type) {
        case 'block':
          return '<div>' . $this->renderStyledText($data, $content) . '</div>';
        case 'linebreak':
          return '<br>';
        case 'paragraph':
          return '<p>' . $this->renderStyledText($data, $content) . '</p>';
        case 'text':
          return $this->renderStyledText($data, $content);
        case 'link':
          return $this->renderRichLink($data, $content);
        case 'list':
          return '<ul style="list-style: disc; margin-left: 20px;">'
            . $this->renderRichText($content) . '</ul>';
        case 'listitem':
          return '<li>' . $this->renderStyledText($data, $content) . '</li>';
        default:
          return '';
      }
    }, $content));
  }

  private function renderRichLink($data, $content): string
  {

    $linkTemplate = $data['template'];


    if (array_key_exists($linkTemplate, $this->renderUtilPerLinkTemplateMap)) {
      return $this->renderUtilPerLinkTemplateMap[$linkTemplate]->renderLink($data, $content, function (...$args) {
        return $this->renderRichText(...$args);
      });
    } else {
      return '[Unsupported Link Template: ' . $linkTemplate . ']';
    }
  }


  private function renderStyledText($data, $content): string
  {

    if (is_string($content)) return '<span>' . $content . '</span>';

    $formatTemplate = null;
    if (isset($data['format'])) {
      $formatTemplate = $data['format'];
    } else if (isset($data['data-fs-style'])) {
      $formatTemplate = $data['data-fs-style'];
    }

    if (!is_null($formatTemplate)) {
      if (array_key_exists($formatTemplate, $this->renderUtilPerFormatTemplateMap)) {
        return $this->renderUtilPerFormatTemplateMap[$formatTemplate]->renderText($data, $content, function (...$args) {
          return $this->renderRichText(...$args);
        });
      } else {
        return '[Unsupported Format Template: ' . json_encode($content) . '-' . json_encode($data) . ']';
      }
    }

    return '<span>' . $this->renderRichText($content) . '</span>';
  }
}
