<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig;

use Spryker\Shared\Log\LoggerTrait;
use Twig\Environment;
use Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentFactory;
use Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentConfig;

/**
 * Utility class to render sections.
 */
class FirstSpiritSectionRenderUtil
{
  use LoggerTrait;

  protected Environment $twig;
  protected FirstSpiritPreviewContentFactory $factory;

  public function __construct(Environment $twig, FirstSpiritPreviewContentFactory $factory)
  {
    $this->twig = $twig;
    $this->factory = $factory;
  }

  /**
   * Renders rich texts recursivly.
   * 
   * @param $content Contents of the fs_text element of the API response.
   */
  public function renderSection($section): string
  {
    $cacheKey = md5(json_encode($section));
    if ($this->getFactory()->getStorageClient()->hasRenderedTemplate($cacheKey)) {
      $cacheResult = $this->getFactory()->getStorageClient()->getRenderedTemplate($cacheKey);
      $this->getLogger()->info('[FirstSpiritPreviewContentDataTwigFunction] Found in cache ' . $section['previewId'] . ' (cache key=' . $cacheKey . ')');
      return $cacheResult;
    } else {
      try {
        $template = $this->getTemplateForSection($section);
        $this->getLogger()->info('[FirstSpiritPreviewContentDataTwigFunction] Attempting to render section ' . $section['previewId'] . ' with template ' . $template);
        $renderedBlock = $this->twig->render('@CmsBlock/template/fs_content_block.twig', [
          'fsData' => $section,
          'template' => $template
        ]);
        $this->getFactory()->getStorageClient()->setRenderedTemplate($cacheKey, $renderedBlock);
        $this->getLogger()->info('[FirstSpiritPreviewContentDataTwigFunction] Finished rendering section ' . $section['previewId']);
        return $renderedBlock;
      } catch (\Throwable $th) {
        $this->getLogger()->error(sprintf(
          '[FirstSpiritPreviewContentDataTwigFunction] Error during rendering of section %s: %s\n%s',
          $section['previewId'],
          $th->getMessage(),
          $th->getTraceAsString()
        ));

        if ($this->getConfig()->shouldDisplayBlockRenderErrors()) {
          // If errors should be displayed, re-throw so error page with details is displayed
          throw $th;
        }
        $isPreview = $this->getFactory()->getPreviewService()->isPreview();
        if ($isPreview) {
          // In preview, render basic information
          return $this->getErrorMessage($th);
        }
      }
    }
  }

  /**
   * Constructs an error message to display from the given error.
   */
  public function getErrorMessage(\Throwable $th): string
  {
    $isPreview = $this->getFactory()->getPreviewService()->isPreview();
    if (!$isPreview) {
      return '';
    }
    return '<div style="text-align: center"><h2>Error</h2>' . $th->getMessage() . '</div>';
  }

  /**
   * Maps the type of the given section to a template.
   *
   * @param mixed $section The section as retrieved from the API.
   * @return string The name of the template to use for rendering.
   */
  private function getTemplateForSection($section): string
  {
    switch ($section['sectionType']) {
      case 'text_image':
        return 'fs-text-image';
    }
    return 'fs-data-visualizer';
  }

  private function getFactory()
  {
    return $this->factory;
  }

  private function getConfig()
  {
    return $this->getFactory()->getConfig();
  }
}
