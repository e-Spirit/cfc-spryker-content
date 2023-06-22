<?php

namespace Crownpeak\Client\FirstSpiritPreviewCaaS;

interface FirstSpiritPreviewCaaSClientInterface
{
    /**
     * @param string $url
     * @param int $id
     * @param string $type
     * @param string $language
     * @return array
     */
    public function fetchCaaSDataFromUrl(string $url, int $id, string $type, string $language): array;
}
