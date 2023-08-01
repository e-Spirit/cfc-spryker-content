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

    /**
     * @return int Retrieves configuration value of cache duration for rendered templates.
     */
    public function getRenderedTemplateCacheDuration(): int
    {
        return $this->get(FirstSpiritPreviewContentConstants::FIRSTSPIRIT_PREVIEW_RENDERED_TEMPLATE_CACHE_DURATION, 0);
    }

    /**
     * @return int Retrieves configuration value of cache duration for API responses.
     */
    public function getApiResponseCacheDuration(): int
    {
        return $this->get(FirstSpiritPreviewContentConstants::FIRSTSPIRIT_PREVIEW_API_RESPONSE_CACHE_DURATION, 0);
    }

    /**
     * @return bool Retrieves configuration value of whether to display CMS block rendering errors.
     */
    public function shouldDisplayBlockRenderErrors(): bool
    {
        return $this->get(FirstSpiritPreviewContentConstants::FIRSTSPIRIT_PREVIEW_DISPLAY_BLOCK_RENDER_ERRORS, false);
    }
}
