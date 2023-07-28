<?php


namespace Crownpeak\Yves\FirstSpiritPreviewContent\Dependency\Client;

use Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentConfig;
use Spryker\Client\Storage\StorageClient;
use Spryker\Shared\Log\LoggerTrait;

/**
 * Bridge to storage client.
 */
class FirstSpiritStorageClientBridge
{

    use LoggerTrait;

    private StorageClient $storageClient;

    private const RENDERED_TEMPLATE_PREFIX = 'RENDERED_TEMPLATE';
    private const API_RESPONSE_PREFIX = 'API_RESPONSE';
    private const RENDERED_TEMPLATE_DEFAULT_TTL = 60 * 60 * 24 * 7;
    private const API_RESPONSE_DEFAULT_TTL = 60 * 5;

    private int $renderedTemplateTtl;
    private int $apiResponseTtl;

    /**
     * @param $storageClient
     */
    public function __construct(StorageClient $storageClient, FirstSpiritPreviewContentConfig $config)
    {
        $this->storageClient = $storageClient;

        $this->renderedTemplateTtl = static::RENDERED_TEMPLATE_DEFAULT_TTL;
        if (is_int($config->getRenderedTemplateCacheDuration()) && $config->getRenderedTemplateCacheDuration() > 0) {
            $this->renderedTemplateTtl = $config->getRenderedTemplateCacheDuration();
        }
        $this->apiResponseTtl = static::API_RESPONSE_DEFAULT_TTL;
        if (is_int($config->getApiResponseCacheDuration()) && $config->getApiResponseCacheDuration() > 0) {
            $this->apiResponseTtl = $config->getApiResponseCacheDuration();
        }
    }

    /**
     * Gets the rendered template with the given key from the cache.
     *
     * @param string $key Key to get for.
     * @return string
     */
    public function getRenderedTemplate(string $key)
    {
        return $this->storageClient->get(static::RENDERED_TEMPLATE_PREFIX . $key);
    }

    /**
     * Sets the rendered template with the given key in the cache.
     *
     * @param string $key Key to save for.
     * @param string $renderedTemplate The rendered template.
     */
    public function setRenderedTemplate(string $key, string $renderedTemplate)
    {
        $this->storageClient->set(static::RENDERED_TEMPLATE_PREFIX . $key, $renderedTemplate, $this->renderedTemplateTtl);
    }

    /**
     * Checks if a rendered template has been saved for the given key.
     *
     * @param string $key Key to save for.
     * @return bool
     */
    public function hasRenderedTemplate(string $key): bool
    {

        $result = $this->getRenderedTemplate($key);
        return is_string($result) && strlen($result) > 0;
    }

    /**
     * Gets the API response with the given key from the cache.
     *
     * @param string $key Key to get for.
     * @return mixed
     */
    public function getApiResponse(string $key)
    {
        return $this->storageClient->get(static::API_RESPONSE_PREFIX . $key);
    }

    /**
     * Sets the API response with the given key in the cache.
     *
     * @param string $key Key to save for.
     * @param mixed $response The API response.
     */
    public function setApiResponse(string $key, mixed $response)
    {
        $this->storageClient->set(static::API_RESPONSE_PREFIX . $key, json_encode($response), $this->apiResponseTtl);
    }

    /**
     * Checks if an API response has been saved for the given key.
     *
     * @param string $key Key to save for.
     * @return bool
     */
    public function hasApiResponse(string $key): bool
    {
        $result = $this->getApiResponse($key);
        return isset($result);
    }
}
