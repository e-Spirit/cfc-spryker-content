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

  private const HEADER_REFERER = 'Referer';

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
   * This method has to be called in an EventDispatcherPlugin to store information about the preview mode.
   * 
   * @param Request $request
   * @return bool Whether the preview authentication is being initialized through the init token.
   */
  public function isPreviewAuthenticationRequested(Request $request): void
  {
    // Check token in URL
    if (!$this->isPreview()) {
      $configuredToken = $this->factory->getConfig()->getAuthenticationToken();
      $receivedToken = $request->get('firstSpiritPreview');
      $this->getLogger()->info('[FirstSpiritPreviewService] Checking for preview mode... Given: ' . $receivedToken . ', Configured: ' . $configuredToken);

      if ($receivedToken === $configuredToken) {
        $this->getLogger()->info('[FirstSpiritPreviewService] Preview mode enabled based on token');
        $this->factory->getSessionClient()->setFsPreviewModeKey($receivedToken);
      }
    }

    // Check referer
    $yvesHost = $request->getSchemeAndHttpHost();
    $refererFromRequest = $request->headers->get(self::HEADER_REFERER);
    // Checks whether the referer starts with the Yves host
    $isRequestFromYves = str_starts_with($refererFromRequest, $yvesHost);
    $refererFromSession = $this->factory->getSessionClient()->getReferer();

    $this->getLogger()->info('[FirstSpiritPreviewService] Referers - Request: ' . $refererFromRequest . ', Session: ' . $refererFromSession);
    $this->getLogger()->info('[FirstSpiritPreviewService] isRequestFromYves: ' . ($isRequestFromYves ? 'true' : 'false'));

    if (empty($refererFromRequest)) {
      // Initial request by entering URL in browser
      // Comment in this code to force a new session state (preview or release) when a page is opened
      // $this->getLogger()->info('[FirstSpiritPreviewService] Initial request detected, clearing stored referer');
      // $this->factory->getSessionClient()->setReferer('');
      // $this->factory->setReferer('');
      // $refererFromSession = '';
    } else if (!$isRequestFromYves) {
      // Request has not been triggered by navigating in Yves, i.e. referer is IFrame parent
      $this->getLogger()->info('[FirstSpiritPreviewService] Using referer from request: ' . $refererFromRequest);
      $this->factory->getSessionClient()->setReferer($refererFromRequest);
      $this->factory->setReferer($refererFromRequest);
    } else if (!empty($refererFromSession)) {
      // Request has been triggered by navigating in Yves but a referer is stored in the session
      $this->getLogger()->info('[FirstSpiritPreviewService] Using referer from session: ' . $refererFromSession);
      $this->factory->setReferer($refererFromSession);
    }
  }
}
