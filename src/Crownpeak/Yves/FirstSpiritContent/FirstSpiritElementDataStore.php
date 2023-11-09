<?php

namespace Crownpeak\Yves\FirstSpiritContent;

use Spryker\Shared\Log\LoggerTrait;

/**
 * Stores information about the currently displayed FS element.
 */
class FirstSpiritElementDataStore
{
    use LoggerTrait;

    private mixed $currentPageData = null;
    private ?\Throwable $error = null;

    /**
     * Sets the FS data for the current page.
     * 
     * @param mixed $data The FS data for the current page. Null if no pendant exists.
     */
    public function setCurrentPage(mixed $data)
    {
        if (empty($data) || is_null($data)) {
            $this->getLogger()->warning('[ContentDataStore] Not setting empty result');
            $this->currentPageData = null;
        } else {
            $this->getLogger()->debug('[ContentDataStore] Setting data for current page ' . $data['previewId']);
            $this->currentPageData = $data;
        }
        $this->error = null;
    }

    /**
     * Gets the FS data for the current page.
     * 
     */
    public function getCurrentPage()
    {
        if (!is_null($this->currentPageData)) {
            $this->getLogger()->debug('[ContentDataStore] Getting data for current page ' . $this->currentPageData['previewId']);
            return $this->currentPageData;
        }
        $this->getLogger()->warning('[ContentDataStore] No data set for current page');
        return null;
    }

    /**
     * Sets the error that occured when fetching data from the API.
     * Error will automatically be re-set when new page is set.
     * 
     * @param \Throwable $th The error that occured.
     */
    public function setError(\Throwable $th)
    {
        $this->error = $th;
    }

    /**
     * Gets the error that occured when fetching data from the API.
     * Returns null of no error has occured during latest fetch.
     * 
     * @return \Throwable $th The error that occured.
     */
    public function getError()
    {
        return $this->error;
    }
}
