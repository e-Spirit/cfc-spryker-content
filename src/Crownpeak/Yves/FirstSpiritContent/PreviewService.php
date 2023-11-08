<?php

namespace Crownpeak\Yves\FirstSpiritContent;

use Spryker\Shared\Log\LoggerTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides information about the current preview state.
 */
class PreviewService
{
    use LoggerTrait;

    private const HEADER_REFERER = 'Referer';

    private FirstSpiritContentFactory $factory;

    public function __construct(FirstSpiritContentFactory $factory)
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
            $this->getLogger()->debug('[PreviewService] Checking for preview mode... Given: ' . $receivedToken . ', Configured: ' . $configuredToken);

            if ($receivedToken === $configuredToken) {
                $this->getLogger()->debug('[PreviewService] Preview mode enabled based on token');
                $this->factory->getSessionClient()->setFsPreviewModeKey($receivedToken);
            }
        }

        // Check referer
        $yvesHost = $request->getSchemeAndHttpHost();
        $refererFromRequest = $request->headers->get(self::HEADER_REFERER);
        // Checks whether the referer starts with the Yves host
        $isRequestFromYves = str_starts_with($refererFromRequest, $yvesHost);
        $refererFromSession = $this->factory->getSessionClient()->getReferer();

        $this->getLogger()->debug('[PreviewService] Referers - Request: ' . $refererFromRequest . ', Session: ' . $refererFromSession);

        if (empty($refererFromRequest)) {
            // Initial request by entering URL in browser
            // Comment in this code to force a new session state (preview or release) when a page is opened
            // $this->getLogger()->debug('[PreviewService] Initial request detected, clearing stored referer');
            // $this->factory->getSessionClient()->setReferer('');
            // $this->factory->setReferer('');
            // $refererFromSession = '';
        } else if (!$isRequestFromYves) {
            // Request has not been triggered by navigating in Yves, i.e. referer is IFrame parent
            $this->getLogger()->debug('[PreviewService] Using referer from request: ' . $refererFromRequest);
            $this->factory->getSessionClient()->setReferer($refererFromRequest);
            $this->factory->setReferer($refererFromRequest);
        } else if (!empty($refererFromSession)) {
            // Request has been triggered by navigating in Yves but a referer is stored in the session
            $this->getLogger()->debug('[PreviewService] Using referer from session: ' . $refererFromSession);
            $this->factory->setReferer($refererFromSession);
        }
    }
}
