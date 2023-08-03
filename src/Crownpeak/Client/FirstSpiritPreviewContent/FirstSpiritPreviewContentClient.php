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

    public function __construct(string $apiHost)
    {
        $this->apiHost = $apiHost;
    }

    /**
     * @param mixed $id
     * @param string $type
     * @param string $locale
     * @return array
     * @throws FirstSpiritPreviewContentClientException
     */
    public function findPage(mixed $id, string $type, string $locale): array
    {
        $url = $this->apiHost . (str_ends_with($this->apiHost, '/') ? '' : '/') . 'findpage';

        if (empty($url)) {
            $this->getLogger()->error('[FirstSpiritContentRequester] No API host set');
            throw new FirstSpiritPreviewContentClientException('No API host set');
        }

        $data = $this->performRequest($url, array(
            'id' => $id,
            'type' => $type,
            'locale' => $locale,
        ));

        return $data;
    }


    /**
     * @param string $fsPageId
     * @param string $locale
     * @return array
     * @throws FirstSpiritPreviewContentClientException
     */
    public function findElement(mixed $fsPageId, string $locale): array
    {
        $url = $this->apiHost . (str_ends_with($this->apiHost, '/') ? '' : '/') . 'findelement';

        if (empty($url)) {
            $this->getLogger()->error('[FirstSpiritContentRequester] No API host set');
            throw new FirstSpiritPreviewContentClientException('No API host set');
        }

        $data = $this->performRequest($url, array(
            'fsPageId' => $fsPageId,
            'locale' => $locale,
        ));

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

    private function performRequest(string $url, mixed $params): mixed
    {

        $query = http_build_query($params);

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
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErrNo = curl_errno($ch);

        if ($httpCode >= 400) {
            $this->getLogger()->error('[FirstSpiritContentRequester] Failed to fetch: ' . $url . ' (HTTP status ' . $httpCode . ')');
            throw new FirstSpiritPreviewContentClientException('Failed to fetch (HTTP status ' . $httpCode . ')');
        }
        if ($curlErrNo) {
            $this->getLogger()->error('[FirstSpiritContentRequester] Failed to fetch: ' . $url . ' (cURL error ' . $curlErrNo . ')');
            throw new FirstSpiritPreviewContentClientException('Failed to fetch (cURL error ' . $curlErrNo . ')');
        }

        // Do soft logging if the url is not reachable
        $data = array();
        $items = 0;
        if ($curlData === false) {
            $this->getLogger()->error('[FirstSpiritContentRequester] No data received: ' . $url);
        } else {
            $data = json_decode($curlData, true);
        }

        curl_close($ch);

        $this->getLogger()->info('[FirstSpiritContentRequester] Found ' . $items . ' elements');

        return $data;
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
