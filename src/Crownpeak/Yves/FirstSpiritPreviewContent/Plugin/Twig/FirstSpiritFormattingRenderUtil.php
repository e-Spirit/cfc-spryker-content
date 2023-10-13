<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig;

use Spryker\Shared\Log\LoggerTrait;
use Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentFactory;

/**
 * Utility class to render sections.
 */
class FirstSpiritFormattingRenderUtil
{
  use LoggerTrait;

  private FirstSpiritPreviewContentFactory $factory;
  private string $locale;

  public function __construct(FirstSpiritPreviewContentFactory $factory, string $locale)
  {
    $this->factory = $factory;
    $this->locale = $locale;
  }

  public function getSupportedLinkTemplates(): array
  {
    return [
      'dom_external_link',
      'dom_content_link',
      'dom_product_link',
      'dom_category_link'
    ];
  }

  public function getSupportedFormatTemplates(): array
  {
    return [
      'bold',
      'italic',
      'subline',
      'format.h2',
      'format.h3',
      'format.subline',
      'format.spryker_special',
    ];
  }

  public function renderLink($data, $content, $renderContentFunction): string
  {
    // TODO: Add links to product and category pages
    $url = '#';
    $target = '';
    if (isset($data['template'])) {
      switch ($data['template']) {
        case 'dom_external_link':
          // External links
          if (isset($data['data']['lt_linkUrl'])) {
            $url = $data['data']['lt_linkUrl'];
            $target = ' target="_blank" ';
          }
          break;
        case 'dom_content_link':
          // Content page links
          break;
        case 'dom_category_link':
          // Category page links
          if (isset($data['data']['lt_category']) && count($data['data']['lt_category']['value']) === 1) {
            $categoryId = $data['data']['lt_category']['value'][0]['identifier'];
            $categoryStorageClient = $this->factory->getCategoryStorageClient();
            $categoryStorageData = $categoryStorageClient->getCategoryNodeById($categoryId, $this->locale);
            if (isset($categoryStorageData['url'])) {
              $url = $categoryStorageData['url'];
            }
          }
          break;
        case 'dom_product_link':
          // Product page links
          if (isset($data['data']['lt_product']) && count($data['data']['lt_product']['value']) === 1) {
            $productId = $data['data']['lt_product']['value'][0]['identifier'];
            $productStorageClient = $this->factory->getProductStorageClient();
            $productStorageData = $productStorageClient->getProductInfoById($productId, $this->locale);
            if (isset($productStorageData['url'])) {
              $url = $productStorageData['url'];
            }
          }
          break;
      }
    }
    return '<a href="' . $url . '" ' . $target . '>' . $renderContentFunction($content) . '</a>';
  }


  public function renderText($data, $content, $renderContentFunction): string
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


    return '<' . $element . ' style="' . $style . '">' . $renderContentFunction($content) . '</' . $element . '>';
  }
}
