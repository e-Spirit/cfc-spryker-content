<?php

namespace Crownpeak\Yves\FirstSpiritContent;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use Crownpeak\Shared\FirstSpiritContent\FirstSpiritContentConstants;

/**
 * Config for Content Data Preview.
 */
class FirstSpiritContentConfig extends AbstractBundleConfig
{
    /**
     * @return string Retrieves configuration value of FS Content endpoint url.
     */
    public function getContentEndpointScript(): string
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_FRONTEND_API_SERVER_URL, '');
    }

    /**
     * @return string Retrieves configuration value of authentication token to init preview.
     */
    public function getAuthenticationToken(): string
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_AUTHENTICATION_TOKEN, '');
    }


    /**
     * @return string The host of the ESpirit web interface.
     */
    public function getFsWebHost(): string
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_WEB_HOST, '');
    }

    /**
     * @return int Retrieves configuration value of cache duration for rendered templates.
     */
    public function getRenderedTemplateCacheDuration(): int
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_RENDERED_TEMPLATE_CACHE_DURATION, 0);
    }

    /**
     * @return int Retrieves configuration value of cache duration for API responses.
     */
    public function getApiResponseCacheDuration(): int
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_API_RESPONSE_CACHE_DURATION, 0);
    }

    /**
     * @return bool Retrieves configuration value of whether to display CMS block rendering errors.
     */
    public function shouldDisplayBlockRenderErrors(): bool
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_DISPLAY_BLOCK_RENDER_ERRORS, false);
    }

    /**
     * @return array Returns the configured section template mapping.
     */
    public function getSectionTemplateMapping(): array
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_SECTION_TEMPLATE_MAPPING, []);
    }

    /**
     * @return array Returns the configured DOM editor template mapping.
     */
    public function getDomEditorTemplateMapping(): array
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_DOM_EDITOR_TEMPLATE_MAPPING, []);
    }

    /**
     * @return string Returns the configured content page URL prefix.
     */
    public function getContentPageUrlPrefix(): string
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_CONTENT_PAGE_URL_PREFIX, 'content');
    }

    /**
     * @return array Returns the configured content page template mapping.
     */
    public function getContentPageTemplateMapping(): array
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_CONTENT_PAGE_TEMPLATE_MAPPING, []);
    }

    /**
     * @return string Retrieves configuration value of rendering script url.
     */
    public function getRenderingScriptUrl(): string
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_SCRIPT_URL, '');
    }

    /**
     * @return string Retrieves configuration value of rendering script log level.
     */
    public function getRenderingScriptLogLevel(): string
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_SCRIPT_LOG_LEVEL, '');
    }

    /**
     * @return string Retrieves configuration value of rendering script base url.
     */
    public function getBaseRenderingScriptUrl(): string
    {
        return $this->get(FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_SCRIPT_BASE_URL, '');
    }
}
