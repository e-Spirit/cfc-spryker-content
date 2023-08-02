<?php

namespace Crownpeak\Client\FirstSpiritPreviewContent;

/*
 * FirstSpiritPreviewContent Client Interface.
 */

interface FirstSpiritPreviewContentClientInterface
{
    /**
     * @param mixed $id
     * @param string $type
     * @param string $language
     * @return array
     */
    public function fetchContentDataFromUrl(mixed $id, string $type, string $language): array;


    /**
     * Sets the referer value to use when performing requests.
     * 
     * @param string $referer The value to set.
     */
    public function setReferer(string $referer): void;

    /**
     * Sets the host of the CFC Frontend API backend.
     * 
     * @param string $host The value to set.
     */
    public function setApiHost(string $host): void;
}
