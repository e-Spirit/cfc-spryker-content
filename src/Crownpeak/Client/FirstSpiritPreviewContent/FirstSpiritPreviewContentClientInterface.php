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
}
