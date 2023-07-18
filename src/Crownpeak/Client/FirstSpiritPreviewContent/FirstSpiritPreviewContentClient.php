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

        $this->getLogger()->info('[FirstSpiritContentRequester] Content request url: ' . $url . ' with x-referrer ' . $this->referer);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-Referrer: ' . $this->referer
        ]);
        $curlData = curl_exec($ch);

        // Do soft logging if the url is not reachable
        $data = array();
        if ($curlData === false) {
            $this->getLogger()->info('[FirstSpiritContentRequester] URL Not Reachable: ' . $url);
        } else {
            $data = json_decode($curlData, true);
        }

        curl_close($ch);

        $this->getLogger()->info('[FirstSpiritContentRequester] Found ' . count($data['items']) . ' elements');
        if (!is_null($data['items'][0])) {
            foreach ($data['items'][0]['children'] as $slot) {
                $this->getLogger()->info('[FirstSpiritContentRequester] Found ' . count($slot['children']) . ' sections for slot ' . $slot['name']);
            }
        }

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
