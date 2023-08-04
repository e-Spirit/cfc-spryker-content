<?php

namespace Crownpeak\Shared\FirstSpiritPreviewContent;

/**
 * Constants interface.
 */
interface FirstSpiritPreviewContentConstants
{
    /**
     * Specification:
     * - Frontend API Server Url
     */
    public const FIRSTSPIRIT_FRONTEND_API_SERVER_URL = 'FIRSTSPIRIT_PREVIEW_CONTENT:FRONTEND_API_SERVER_URL';
    /**
     * Specification:
     * - Whether to show CMS block render errors
     */
    public const FIRSTSPIRIT_PREVIEW_DISPLAY_BLOCK_RENDER_ERRORS = 'FIRSTSPIRIT_PREVIEW:DISPLAY_BLOCK_RENDER_ERRORS';

    public const FIRSTSPIRIT_PREVIEW_RENDERED_TEMPLATE_CACHE_DURATION = 'FIRSTSPIRIT_PREVIEW_RENDERED_TEMPLATE_CACHE_DURATION';
    public const FIRSTSPIRIT_PREVIEW_API_RESPONSE_CACHE_DURATION = 'FIRSTSPIRIT_PREVIEW_API_RESPONSE_CACHE_DURATION';

    public const FIRSTSPIRIT_SECTION_TEMPLATE_MAPPING = 'FIRSTSPIRIT_SECTION_TEMPLATE_MAPPING';
}
