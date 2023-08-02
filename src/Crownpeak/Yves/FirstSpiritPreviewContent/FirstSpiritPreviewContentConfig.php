<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent;

use Crownpeak\Shared\FirstSpiritPreview\FirstSpiritPreviewConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;
use Crownpeak\Shared\FirstSpiritPreviewContent\FirstSpiritPreviewContentConstants;

/**
 * Config for Content Data Preview.
 */
class FirstSpiritPreviewContentConfig extends AbstractBundleConfig
{
    /**
     * @return string Retrieves configuration value of FS Content endpoint url.
     */
    public function getContentEndpointScript(): string
    {
        return $this->get(FirstSpiritPreviewContentConstants::FIRSTSPIRIT_FRONTEND_API_SERVER_URL, '');
    }

    /**
     * @return string Retrieves configuration value of authentication token to init preview.
     */
    public function getAuthenticationToken(): string
    {
        return $this->get(FirstSpiritPreviewConstants::FIRSTSPIRIT_PREVIEW_AUTHENTICATION_TOKEN, '');
    }
}
