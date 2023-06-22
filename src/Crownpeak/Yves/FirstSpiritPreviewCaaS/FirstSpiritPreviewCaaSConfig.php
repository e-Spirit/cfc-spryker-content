<?php
namespace Crownpeak\Yves\FirstSpiritPreviewCaaS;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use Crownpeak\Shared\FirstSpiritPreviewCaaS\FirstSpiritPreviewCaaSConstants;

/**
 * Config for CAAS Data Preview.
 */
class FirstSpiritPreviewCaaSConfig extends AbstractBundleConfig
{
    /**
     * @return string Retrieves configuration value of CAAS endpoint url.
     */
    public function getCaasEndpointScript(): string
    {
        return $this->get(FirstSpiritPreviewCaaSConstants::FIRSTSPIRIT_PREVIEW_CAAS_SCRIPT_URL, '');
    }
}
