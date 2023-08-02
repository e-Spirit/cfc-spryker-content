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

    private string $referer = '';
    private string $apiHost = '';

    /**
     * @param mixed $id
     * @param string $type
     * @param string $language
     * @return array
     * @throws FirstSpiritPreviewContentClientException
     */
    public function fetchContentDataFromUrl(mixed $id, string $type, string $language): array
    {
        $url = $this->apiHost;

        if (empty($url)) {
            $this->getLogger()->error('[FirstSpiritContentRequester] No API host set');
            throw new FirstSpiritPreviewContentClientException('No API host set');
        }

        $query = http_build_query(
            array(
                'id' => $id,
                'type' => $type,
                'locale' => $language,
            )
        );

        $url = $url . $this->getNextQueryParam($url) . $query;

        $this->getLogger()->info('[FirstSpiritContentRequester] Content request url: ' . $url . ' with x-referrer ' . $this->referer);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-Referrer: ' . $this->referer
        ]);
        // Set timeout low so waiting for cURL does not let whole page time out
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $curlData = curl_exec($ch);
        if (curl_errno($ch)) {
            $this->getLogger()->error('[FirstSpiritContentRequester] Failed to fetch: ' . $url . ' (cURL error ' . curl_errno($ch) . ')');
        }

        // Do soft logging if the url is not reachable
        $data = array();
        $items = 0;
        if ($curlData === false) {
            $this->getLogger()->error('[FirstSpiritContentRequester] No data received: ' . $url);
        } else {
            $data = json_decode($curlData, true);
            $items = count($data['items']);
            // Log slot contents for debugging purposes
            if (!(empty($data['items'][0]))) {
                foreach ($data['items'][0]['children'] as $slot) {
                    $this->getLogger()->info('[FirstSpiritContentRequester] Found ' . count($slot['children']) . ' sections for slot ' . $slot['name']);
                }
            }
        }

        curl_close($ch);

        $this->getLogger()->info('[FirstSpiritContentRequester] Found ' . $items . ' elements');

        return $data;
    }

    /**
     * Sets the referer value to use when performing requests.
     * 
     * @param string $referer The value to set.
     */
    public function setReferer(string $referer): void
    {
        $this->referer = $referer;
    }

    /**
     * Sets the host of the CFC Frontend API backend.
     * 
     * @param string $host The value to set.
     */
    public function setApiHost(string $host): void
    {
        $this->apiHost = $host;
    }

    /**
     * @param string $url
     * @return string
     */
    private function getNextQueryParam(string $url)
    {
        if (strpos($url, '?')) {
            return '&';
        } else {
            return '?';
        }
    }
}
