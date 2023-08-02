<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent;

use Spryker\Shared\Log\LoggerTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides information about the current preview state.
 */
class FirstSpiritPreviewService
{
  use LoggerTrait;

  private FirstSpiritPreviewContentFactory $factory;

  public function __construct(FirstSpiritPreviewContentFactory $factory)
  {
    $this->factory = $factory;
  }

  /**
   * @return bool Whether the page is loaded in preview.
   */
  public function isPreview(): bool
  {
    if ($this->factory->getSessionClient()->hasFsPreviewModeKey()) {
      if (is_string($this->factory->getSessionClient()->getFsPreviewModeKey())) {
        return $this->factory->getSessionClient()->getFsPreviewModeKey() == $this->factory->getConfig()->getAuthenticationToken();
      }
    }
    return false;
  }

  /**
   * @param Request $request
   * @return bool Whether the preview authentication is being initialized through the init token.
   */
  public function isPreviewAuthenticationRequested(Request $request): void
  {
    if ($this->isPreview()) {
      $this->getLogger()->info('[FirstSpiritPreviewService] Preview already active');
      // If preview is already active, do nothing
      return;
    }
    $configuredToken = $this->factory->getConfig()->getAuthenticationToken();
    $receivedToken = $request->get('firstSpiritPreview');
    $this->getLogger()->info('[FirstSpiritPreviewService] Checking for preview mode... Given: ' . $receivedToken . ', Configured: ' . $configuredToken);
    if ($receivedToken === $configuredToken) {
      $this->getLogger()->info('[FirstSpiritPreviewService] Preview mode enabled');
      $this->factory->getSessionClient()->setFsPreviewModeKey($receivedToken);
    }
  }
}
