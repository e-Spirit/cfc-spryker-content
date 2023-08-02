<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig;

/**
 * Utility class to render rich text elements.
 */
class FirstSpiritRichTextUtil
{

  /**
   * Renders rich texts recursivly.
   * 
   * @param $content Contents of the fs_text element of the API response.
   */
  public function renderRichText($content): string
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
          return '<ul style="list-style: disc;">' . $this->renderRichText($content) . '</ul>';
        case 'listitem':
          return '<li>' . $this->renderStyledText($data, $content) . '</li>';
        default:
          return '';
      }
    }, $content));
  }

  private function renderRichLink($data, $content): string
  {
    // TODO
    return '<a href="#">' . $this->renderRichText($content) . '</a>';
  }

  private function renderStyledText($data, $content): string
  {
    if (is_string($content)) return '<span>' . $content . '</span>';

    $style = '';
    $element = 'span';

    if (isset($data['format'])) {
      switch ($data['format']) {
        case 'bold':
          $style .= ' font-weight: bold;';
          break;
        case 'italic':
          $style .= ' font-style: italic;';
          break;
        case 'subline':
          $style .= ' font-weight: bold; font-size: 1.5em;';
          break;
      }
    }

    if (isset($data['data-fs-style'])) {
      switch ($data['data-fs-style']) {
        case 'format.h2':
          $style .= ' font-size: 2em;';
          $element = 'h2';
          break;
        case 'format.h3':
          $style .= ' font-size: 1.5em;';
          $element = 'h3';
          break;
        case 'format.subline':
          $style .= ' font-weight: bold; font-size: 1.5em;';
          break;
      }
    }


    return '<' . $element . ' style="' . $style . '">' . $this->renderRichText($content) . '</' . $element . '>';
  }
}
