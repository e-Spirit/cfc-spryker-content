<?php
namespace Crownpeak\Yves\FirstSpiritPreviewContent;

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
        return $this->get(FirstSpiritPreviewContentConstants::FIRSTSPIRIT_PREVIEW_CONTENT_SCRIPT_URL, '');
    }
}
