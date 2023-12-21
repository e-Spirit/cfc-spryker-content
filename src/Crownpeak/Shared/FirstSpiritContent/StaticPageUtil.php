<?php

namespace Crownpeak\Shared\FirstSpiritContent;

use Spryker\Shared\Log\LoggerTrait;
use Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentFactory;

/**
 * Utility class to handle static pages.
 */
class StaticPageUtil
{
    use LoggerTrait;

    public FirstSpiritContentFactory $factory;

    public function __construct(FirstSpiritContentFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Returns the URL of the page with the given ID.
     * 
     * @param string $id The ID of the page.
     * @param string $locale The locale to pass.
     * @return ?string The URL of the given page if valid, null otherwise.
     */
    public function getUrl(string $id, string $locale): ?string
    {
        $mapping = $this->getFactory()->getConfig()->getStaticPageUrlMapping();

        if (array_key_exists($id, $mapping)) {
            $urls = $mapping[$id];
            if (is_array($urls)) {
                if (array_key_exists($locale, $urls)) {
                    return $urls[$locale];
                }
                $this->getLogger()->error('[StaticPageUtil] Given locale not configured: ' . $locale . ' (ID=' . $id . ')');
                return null;
            } else if (is_string($urls)) {
                return $urls;
            }
            $this->getLogger()->error('[StaticPageUtil] Invalid URL configured for ID: ' . $id);
            return null;
        }
        $this->getLogger()->error('[StaticPageUtil] No URL configured for ID: ' . $id);
        return null;
    }

    private function getFactory(): FirstSpiritContentFactory
    {
        return $this->factory;
    }
}
