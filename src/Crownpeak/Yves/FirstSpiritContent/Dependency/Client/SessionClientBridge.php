<?php


namespace Crownpeak\Yves\FirstSpiritContent\Dependency\Client;

use Spryker\Client\Session\SessionClient;

/**
 * Bridge implementation to session client.
 * Used to interact with the Spryker session.
 */
class SessionClientBridge
{

    private const FIRSTSPIRIT_PREVIEW_MODE = 'fsPreviewMode';
    private const FIRSTSPIRIT_REFERER = 'fsReferer';

    private SessionClient $sessionClient;

    /**
     * @param $sessionClient
     */
    public function __construct(SessionClient $sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }

    /**
     * Sets the given key as the preview mode key.
     *
     * @param string $key The key to set.
     * @return void
     */
    public function setFsPreviewModeKey(string $key)
    {
        $this->sessionClient->set(self::FIRSTSPIRIT_PREVIEW_MODE, $key);
        $this->sessionClient->save();
    }

    /**
     * Returns the configured preview mode key.
     *
     * @return string The configured preview mode key.
     */
    public function getFsPreviewModeKey(): string
    {
        if ($this->hasFsPreviewModeKey()) {
            return $this->sessionClient->get(self::FIRSTSPIRIT_PREVIEW_MODE);
        }
        return '';
    }

    /**
     * Checks if the session contains the preview mode key.
     *
     * @return bool Whether the session contains the preview mode key.
     */
    public function hasFsPreviewModeKey(): bool
    {

        return $this->sessionClient->has(self::FIRSTSPIRIT_PREVIEW_MODE);
    }

    /**
     * Sets the given referer as the referer in the session.
     *
     * @param string $referer The referer to set.
     * @return void
     */
    public function setReferer(string $referer)
    {
        $this->sessionClient->set(self::FIRSTSPIRIT_REFERER, $referer);
        $this->sessionClient->save();
    }

    /**
     * Returns the configured referer.
     *
     * @return string The configured referer.
     */
    public function getReferer(): string
    {
        if ($this->hasReferer()) {
            return $this->sessionClient->get(self::FIRSTSPIRIT_REFERER);
        }
        return '';
    }

    /**
     * Checks if the session contains the referer.
     *
     * @return bool Whether the session contains the referer.
     */
    public function hasReferer(): bool
    {

        return $this->sessionClient->has(self::FIRSTSPIRIT_REFERER);
    }
}
