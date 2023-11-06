<?php

namespace Crownpeak\Client\FirstSpiritContent;

/*
 * FrontendApiServerClient Interface.
 */

interface FrontendApiServerClientInterface
{
    /**
     * @param mixed $id
     * @param string $type
     * @param string $locale
     * @return array
     */
    public function findPage(mixed $id, string $type, string $locale): array;

    /**
     * @param string $fsPageId
     * @param string $locale
     * @return array
     */
    public function findElement(string $fsPageId, string $locale): array;

    /**
     * @param string $locale
     * @return array
     * @throws FirstSpiritContentClientException
     */
    public function fetchNavigation(string $locale): array;

    /**
     * Sets the referer value to use when performing requests.
     * 
     * @param string $referer The value to set.
     */
    public function setReferer(string $referer): void;
}
