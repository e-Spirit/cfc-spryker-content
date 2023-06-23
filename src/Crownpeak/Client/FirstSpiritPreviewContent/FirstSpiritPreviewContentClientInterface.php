<?php

namespace Crownpeak\Client\FirstSpiritPreviewContent;

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
