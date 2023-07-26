<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Dependency\Client;

/**
 * Interface to session client.
 */
interface FirstSpiritSessionClientBridgeInterface
{
  /**
   * @param string $key
   * @return void
   */
  public function setFsPreviewModeKey(string $key);

  /**
   * @return string
   */
  public function getFsPreviewModeKey(): string;

  /**
   * @return bool
   */
  public function hasFsPreviewModeKey(): bool;
}