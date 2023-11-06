<?php

namespace Crownpeak\Client\FirstSpiritContent;

use Crownpeak\Client\FirstSpiritContent\FrontendApiServerClientInterface;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\Log\LoggerTrait;
use Crownpeak\Yves\FirstSpiritContent\Exception\FirstSpiritContentClientException;

/*
 * Client to fetch data from the CFC Frontend API server / backend..
 */

class FrontendApiServerClient extends AbstractClient implements FrontendApiServerClientInterface
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
     * @throws FirstSpiritContentClientException
     */
    public function findPage(mixed $id, string $type, string $locale): array
    {
        $url = $this->apiHost . (str_ends_with($this->apiHost, '/') ? '' : '/') . 'findpage';

        if (empty($url)) {
            $this->getLogger()->error('[FrontendApiServerClient] No API host set');
            throw new FirstSpiritContentClientException('No API host set');
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
     * @throws FirstSpiritContentClientException
     */
    public function findElement(mixed $fsPageId, string $locale): array
    {
        $url = $this->apiHost . (str_ends_with($this->apiHost, '/') ? '' : '/') . 'findelement';

        if (empty($url)) {
            $this->getLogger()->error('[FrontendApiServerClient] No API host set');
            throw new FirstSpiritContentClientException('No API host set');
        }

        $data = $this->performRequest($url, array(
            'fsPageId' => $fsPageId,
            'locale' => $locale,
        ));

        return $data;
    }

    /**
     * @param string $locale
     * @return array
     * @throws FirstSpiritContentClientException
     */
    public function fetchNavigation(string $locale): array
    {
        $url = $this->apiHost . (str_ends_with($this->apiHost, '/') ? '' : '/') . 'fetchNavigation';

        if (empty($url)) {
            $this->getLogger()->error('[FrontendApiServerClient] No API host set');
            throw new FirstSpiritContentClientException('No API host set');
        }

        $data = $this->performRequest($url, array(
            'initialPath' => '/',
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

        $this->getLogger()->debug('[FrontendApiServerClient] Content request url: ' . $url . ' with x-referrer ' . $this->referer);

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
            $this->getLogger()->error('[FrontendApiServerClient] Failed to fetch: ' . $url . ' (HTTP status ' . $httpCode . ')');
            throw new FirstSpiritContentClientException('Failed to fetch (HTTP status ' . $httpCode . ')');
        }
        if ($curlErrNo) {
            $this->getLogger()->error('[FrontendApiServerClient] Failed to fetch: ' . $url . ' (cURL error ' . $curlErrNo . ')');
            throw new FirstSpiritContentClientException('Failed to fetch (cURL error ' . $curlErrNo . ')');
        }

        // Do soft logging if the url is not reachable
        $data = array();
        $items = 0;
        if ($curlData === false) {
            $this->getLogger()->error('[FrontendApiServerClient] No data received: ' . $url);
        } else {
            $data = json_decode($curlData, true);
        }

        curl_close($ch);

        $this->getLogger()->debug('[FrontendApiServerClient] Found ' . $items . ' elements');

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
