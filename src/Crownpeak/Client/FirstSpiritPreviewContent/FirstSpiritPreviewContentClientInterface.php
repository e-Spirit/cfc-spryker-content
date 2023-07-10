<?php

namespace Crownpeak\Client\FirstSpiritPreviewContent;

/*
 * FirstSpiritPreviewContent Client Interface.
 */
interface FirstSpiritPreviewContentClientInterface
{
    /**
     * @param string $url
     * @param int $id
     * @param string $type
     * @param string $language
     * @return array
     */
    public function fetchContentDataFromUrl(string $url, int $id, string $type, string $language): array;
}
