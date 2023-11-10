<?php

namespace Crownpeak\Client\FirstSpiritContent;

use Crownpeak\Yves\FirstSpiritContent\Exception\ContentClientException;

/*
 * Client to fetch data from the CFC Frontend API server / backend.
 */

interface FrontendApiServerClientInterface
{

    /**
     * Executes findPage call to the CFC Frontend API server and returns the result.
     *
     * @param string $id ID of the page to get.
     * @param string $type Type of the page to get
     * @param string $locale The locale to use for the request.
     * @return ?array The result of findPage call.

     * @throws ContentClientException
     */
    public function findPage(string $id, string $type, string $locale): ?array;

    /**
     * Executes findElement call to the CFC Frontend API server and returns the result.
     * 
     * @param string $fsPageId The ID of the FirstSpirit page.
     * @param string $locale The locale to use for the request.
     * @return ?array The result of findElement call.
     * @throws ContentClientException
     */
    public function findElement(mixed $fsPageId, string $locale): ?array;
    /**
     * Executes fetchNavigation call to the CFC Frontend API server and returns the result.
     * 
     * @param string $locale The locale to use for the request.
     * @return ?array The result of findNavigation call.

     * @throws ContentClientException
     */
    public function fetchNavigation(string $locale): ?array;

    /**
     * Sets the referer value to use when performing requests.
     * 
     * @param string $referer The value to set.
     */
    public function setReferer(string $referer): void;
}
