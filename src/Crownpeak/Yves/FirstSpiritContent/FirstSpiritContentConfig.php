<?php

namespace Crownpeak\Yves\FirstSpiritContent;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use Crownpeak\Shared\FirstSpiritContent\FirstSpiritContentConstants;

/**
 * Config for the content module.
 */
class FirstSpiritContentConfig extends AbstractBundleConfig
{
    /**
     * @return string URL of the CFC Frontend API server / backend to be used by the Spryker server.
     */
    public function getContentEndpointScript(): string
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_FRONTEND_API_SERVER_URL, '');
    }

    /**
     * @return string Authentication token that is appended to the URL via ?firstSpiritPreview=
     */
    public function getAuthenticationToken(): string
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_AUTHENTICATION_TOKEN, '');
    }

    /**
     * @return string The Content Creator host.
     */
    public function getFsWebHost(): string
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_WEB_HOST, '');
    }

    /**
     * @return int Duration of the rendered template cache.
     */
    public function getRenderedTemplateCacheDuration(): int
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_RENDERED_TEMPLATE_CACHE_DURATION, 0);
    }

    /**
     * @return int Duration of the CFC Frontend API server response cache.
     */
    public function getApiResponseCacheDuration(): int
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_API_RESPONSE_CACHE_DURATION, 0);
    }

    /**
     * @return bool Whether to display render errors in detail.
     */
    public function shouldDisplayBlockRenderErrors(): bool
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_DISPLAY_BLOCK_RENDER_ERRORS, false);
    }

    /**
     * @return array Mapping of section templates to Twig templates.
     */
    public function getSectionTemplateMapping(): array
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_SECTION_TEMPLATE_MAPPING, []);
    }

    /**
     * @return array Mapping of DOM editor format templates to Twig templates.
     */
    public function getDomEditorTemplateMapping(): array
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_DOM_EDITOR_TEMPLATE_MAPPING, []);
    }

    /**
     * @return string URL prefix for content pages.
     */
    public function getContentPageUrlPrefix(): string
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_CONTENT_PAGE_URL_PREFIX, 'content');
    }

    /**
     * @return array Mapping of page templates for content pages to Twig templates.
     */
    public function getContentPageTemplateMapping(): array
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_CONTENT_PAGE_TEMPLATE_MAPPING, []);
    }

    /**
     * @return string URL of the CFC Frontend API client script (static.js).
     */
    public function getRenderingScriptUrl(): string
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_SCRIPT_URL, '');
    }

    /**
     * @return string Loglevel to pass to the CFC Frontend API client.
     */
    public function getRenderingScriptLogLevel(): string
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_SCRIPT_LOG_LEVEL, '');
    }

    /**
     * @return string URL of the CFC Frontend API server / backend to be used by the frontend.
     */
    public function getBaseRenderingScriptUrl(): string
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_SCRIPT_BASE_URL, '');
    }
}
