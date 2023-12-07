<?php

namespace Crownpeak\Shared\FirstSpiritContent;

/**
 * Constants interface.
 */
interface FirstSpiritContentConstants
{
    /**
     * Content Creator host.
     */
    public const FIRSTSPIRIT_PREVIEW_WEB_HOST = 'FIRSTSPIRIT_PREVIEW:WEB_HOST';
    /**
     * URL of the CFC Frontend API client script (static.js).
     */
    public const FIRSTSPIRIT_PREVIEW_SCRIPT_URL = 'FIRSTSPIRIT_PREVIEW:SCRIPT_URL';
    /**
     * URL of the CFC Frontend API server / backend to be used by the Spryker server.
     */
    public const FIRSTSPIRIT_FRONTEND_API_SERVER_URL = 'FIRSTSPIRIT_PREVIEW_CONTENT:FRONTEND_API_SERVER_URL';
    /**
     * URL of the CFC Frontend API server / backend to be used by the frontend.
     */
    public const FIRSTSPIRIT_PREVIEW_SCRIPT_BASE_URL = 'FIRSTSPIRIT_PREVIEW_CONTENT:FIRSTSPIRIT_PREVIEW_SCRIPT_BASE_URL';
    /**
     * Loglevel to pass to the CFC Frontend API client.
     */
    public const FIRSTSPIRIT_PREVIEW_SCRIPT_LOG_LEVEL = 'FIRSTSPIRIT_PREVIEW:SCRIPT_LOG_LEVEL';
    /**
     * Authentication token that is appended to the URL via ?firstSpiritPreview=.
     */
    public const FIRSTSPIRIT_PREVIEW_AUTHENTICATION_TOKEN = 'FIRSTSPIRIT_PREVIEW:AUTHENTICATION_TOKEN';
    /**
     * Whether to display render errors in detail.
     */
    public const FIRSTSPIRIT_PREVIEW_DISPLAY_BLOCK_RENDER_ERRORS = 'FIRSTSPIRIT_PREVIEW:DISPLAY_BLOCK_RENDER_ERRORS';
    /**
     * Duration of the rendered template cache.
     */
    public const FIRSTSPIRIT_PREVIEW_RENDERED_TEMPLATE_CACHE_DURATION = 'FIRSTSPIRIT_PREVIEW_RENDERED_TEMPLATE_CACHE_DURATION';
    /**
     * Duration of the CFC Frontend API server response cache.
     */
    public const FIRSTSPIRIT_PREVIEW_API_RESPONSE_CACHE_DURATION = 'FIRSTSPIRIT_PREVIEW_API_RESPONSE_CACHE_DURATION';
    /**
     * Mapping of section templates to Twig templates.
     */
    public const FIRSTSPIRIT_SECTION_TEMPLATE_MAPPING = 'FIRSTSPIRIT_SECTION_TEMPLATE_MAPPING';
    /**
     * Mapping of DOM editor format templates to Twig templates.
     */
    public const FIRSTSPIRIT_DOM_EDITOR_TEMPLATE_MAPPING = 'FIRSTSPIRIT_DOM_EDITOR_TEMPLATE_MAPPING';
    /**
     * URL prefix for content pages.
     */
    public const FIRSTSPIRIT_CONTENT_PAGE_URL_PREFIX = 'FIRSTSPIRIT_CONTENT_PAGE_URL_PREFIX';
    /**
     * Mapping of page templates for content pages to Twig templates.
     */
    public const FIRSTSPIRIT_CONTENT_PAGE_TEMPLATE_MAPPING = 'FIRSTSPIRIT_CONTENT_PAGE_TEMPLATE_MAPPING';
    /**
     * Key to be used in the content page template mapping to map errors to a Twig template.
     */
    public const FIRSTSPIRIT_CONTENT_PAGE_TEMPLATE_MAPPING_ERROR = 'FIRSTSPIRIT_CONTENT_PAGE_TEMPLATE_MAPPING_ERROR';
    /**
     * Mapping of URLs to static page IDs.
     */
    public const FIRSTSPIRIT_STATIC_PAGE_URL_MAPPING = 'FIRSTSPIRIT_STATIC_PAGE_URL_MAPPING';
}
