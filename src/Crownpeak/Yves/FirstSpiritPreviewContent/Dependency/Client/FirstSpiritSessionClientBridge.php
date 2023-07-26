<?php


namespace Crownpeak\Yves\FirstSpiritPreviewContent\Dependency\Client;

use Spryker\Client\Session\SessionClient;

/**
 * Bridge to session client.
 */
class FirstSpiritSessionClientBridge implements FirstSpiritSessionClientBridgeInterface
{

    private const FIRSTSPIRIT_PREVIEW_MODE = "fsPreviewMode";

    /**
     * @var SessionClient $sessionClient
     */
    private  $sessionClient;

    /**
     * @param $sessionClient
     */
    public function __construct($sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }

    /**
     * @param string $key
     * @return void
     */
    public function setFsPreviewModeKey(string $key)
    {
        $this->sessionClient->set(self::FIRSTSPIRIT_PREVIEW_MODE, $key);
        $this->sessionClient->save();
    }

    /**
     * @return string
     */
    public function getFsPreviewModeKey(): string
    {
        if ($this->hasFsPreviewModeKey()) {
            return $this->sessionClient->get(self::FIRSTSPIRIT_PREVIEW_MODE);
        }
        return "";
    }

    /**
     * @return bool
     */
    public function hasFsPreviewModeKey(): bool
    {

        return $this->sessionClient->has(self::FIRSTSPIRIT_PREVIEW_MODE);
    }
}
