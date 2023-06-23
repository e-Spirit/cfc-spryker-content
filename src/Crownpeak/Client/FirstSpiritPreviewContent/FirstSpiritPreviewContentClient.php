<?php
namespace Crownpeak\Client\FirstSpiritPreviewContent;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\Log\LoggerTrait;
use Crownpeak\Yves\FirsSpiritPreviewContent\Exception\FirstSpiritPreviewContentClientException;
use Crownpeak\Client\FirsSpiritPreviewContent\FirsSpiritPreviewContentClientInterface;

class FirstSpiritPreviewCaaSClient extends AbstractClient implements FirsSpiritPreviewContentClientInterface
{
    use LoggerTrait;

    /**
     * @param string $url
     * @param int $id
     * @param string $type
     * @param string $language
     * @return array
     * @throws FirstSpiritPreviewContentClientException
     */
    public function fetchContentDataFromUrl(string $url, int $id, string $type, string $language): array
    {
        $query = http_build_query(
            array(
                'id' => $id,
                'type' => $type,
                'language' => $language,
            )
        );

        $url = $url . $this->getNextQueryParam($url) . $query;

        $this->getLogger()->info('[FirstSpiritContentRequester] Content request url: ' . $url);

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curlData = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new FirstSpiritPreviewContentClientException(curl_error($ch));
        }

        curl_close($ch);

        $data = json_decode($curlData, true);

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
