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

        $this->getLogger()->info('[FirstSpiritContentRequester] Content request url: ' . $url);

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curlData = curl_exec($ch);

        //Do soft logging if the url is not reachable
        $data = array();
        if ($curlData === false) {
            $this->getLogger()->info('[FirstSpiritContentRequester] URL Not Reachable: ' . $url);
        } else {
            $data = json_decode($curlData, true);
        }

        curl_close($ch);

        return $data;
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
