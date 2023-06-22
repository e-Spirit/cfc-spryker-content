<?php
namespace Crownpeak\Client\FirstSpiritPreviewCaaS;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\Log\LoggerTrait;
use Crownpeak\Yves\FirstSpiritPreviewCaaS\Exception\FirstSpiritPreviewCaaSClientException;
use Crownpeak\Client\FirstSpiritPreviewCaaS\FirstSpiritPreviewCaaSClientInterface;

class FirstSpiritPreviewCaaSClient extends AbstractClient implements FirstSpiritPreviewCaaSClientInterface
{
    use LoggerTrait;

    /**
     * @param string $url
     * @param int $id
     * @param string $type
     * @param string $language
     * @return array
     * @throws FirstSpiritPreviewCaaSClientException
     */
    public function fetchCaaSDataFromUrl(string $url, int $id, string $type, string $language): array
    {
        $query = http_build_query(
            array(
                'id' => $id,
                'type' => $type,
                'language' => $language,
            )
        );

        $url = $url . $this->getNextQueryParam($url) . $query;

        $this->getLogger()->info('[FirstSpiritCaaSRequester] CaaS request url: ' . $url);

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curlData = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new FirstSpiritPreviewCaaSClientException(curl_error($ch));
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
