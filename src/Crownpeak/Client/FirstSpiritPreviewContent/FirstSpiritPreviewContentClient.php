<?php

namespace Crownpeak\Client\FirstSpiritPreviewContent;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\Log\LoggerTrait;
use Crownpeak\Yves\FirstSpiritPreviewContent\Exception\FirstSpiritPreviewContentClientException;
use Crownpeak\Client\FirstSpiritPreviewContent\FirstSpiritPreviewContentClientInterface;

/*
 * FirstSpiritPreviewContent Client.
 */

class FirstSpiritPreviewContentClient extends AbstractClient implements FirstSpiritPreviewContentClientInterface
{
    use LoggerTrait;

    private string $referer = "";

    /**
     * @param string $url
     * @param mixed $id
     * @param string $type
     * @param string $language
     * @return array
     * @throws FirstSpiritPreviewContentClientException
     */
    public function fetchContentDataFromUrl(string $url, mixed $id, string $type, string $language): array
    {
        $query = http_build_query(
            array(
                'id' => $id,
                'type' => $type,
                'locale' => $language,
            )
        );

        $url = $url . $this->getNextQueryParam($url) . $query;

        $this->getLogger()->info('[FirstSpiritContentRequester] Content request url: ' . $url . ' with x-referer ' . $this->referer);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-Referer: ' . $this->referer
        ]);
        $curlData = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new FirstSpiritPreviewContentClientException(curl_error($ch));
        }

        curl_close($ch);

        $data = json_decode($curlData, true);

        $this->getLogger()->info('[FirstSpiritContentRequester] Found ' . count($data['items']) . ' elements');

        return $data;
    }

    public function setReferer(string $referer)
    {
        $this->referer = $referer;
    }

    /**
     * @param string $url
     * @return string
     */
    private function getNextQueryParam(string $url)
    {
        if (strpos($url, "?")) {
            return "&";
        } else {
            return "?";
        }
    }
}
