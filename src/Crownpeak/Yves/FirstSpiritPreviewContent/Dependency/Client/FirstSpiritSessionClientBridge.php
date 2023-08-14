<?php


namespace Crownpeak\Yves\FirstSpiritPreviewContent\Dependency\Client;

use Spryker\Client\Session\SessionClient;

/**
 * Bridge to session client.
 */
class FirstSpiritSessionClientBridge implements FirstSpiritSessionClientBridgeInterface
{

    private const FIRSTSPIRIT_PREVIEW_MODE = "fsPreviewMode";
    private const FIRSTSPIRIT_REFERER = "fsReferer";

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

    /**
     * @param string $key
     * @return void
     */
    public function setReferer(string $key)
    {
        $this->sessionClient->set(self::FIRSTSPIRIT_REFERER, $key);
        $this->sessionClient->save();
    }

    /**
     * @return string
     */
    public function getReferer(): string
    {
        if ($this->hasReferer()) {
            return $this->sessionClient->get(self::FIRSTSPIRIT_REFERER);
        }
        return "";
    }

    /**
     * @return bool
     */
    public function hasReferer(): bool
    {

        return $this->sessionClient->has(self::FIRSTSPIRIT_REFERER);
    }
}
