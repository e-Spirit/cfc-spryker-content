<?php

namespace Crownpeak\Client\FirstSpiritPreviewContent;

/*
 * FirstSpiritPreviewContent Client Interface.
 */

interface FirstSpiritPreviewContentClientInterface
{
    /**
     * @param string $url
     * @param mixed $id
     * @param string $type
     * @param string $language
     * @return array
     */
    public function fetchContentDataFromUrl(string $url, mixed $id, string $type, string $language): array;


    /**
     * Sets the referer value to use when performing requests.
     * 
     * @param string $referer The value to set.
     */
    public function setReferer(string $referer): void;
}
