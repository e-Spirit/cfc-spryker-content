<?php

namespace Crownpeak\Yves\FirstSpiritContent\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentFactory getFactory()
 * @method \Crownpeak\Yves\FirstSpiritContent\FirstSpiritContentConfig getConfig()
 */
class FirstSpiritContentEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    use LoggerTrait;

    protected const EVENT_PRIORITY = -257;

    /**
     * @const string
     */
    protected const HEADER_REFERER = 'Referer';

    /**
     * @const string
     */
    protected const HEADER_CONTENT_SECURITY_POLICY = 'Content-Security-Policy';

    /**
     * @const string
     */
    protected const SAME_SITE_ATTRIBUTE_VALUE = Cookie::SAMESITE_NONE;

    /**
     * {@inheritDoc}
     * - Sets store custom information in headers.
     *
     * @api
     *
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher->addListener(
            KernelEvents::REQUEST,
            function (RequestEvent $event) {
                if (!$event->isMainRequest()) {
                    return;
                }
                $request = $event->getRequest();

                $previewService = $this->getFactory()->getPreviewService();
                $previewService->isPreviewAuthenticationRequested($request);
            },
            static::EVENT_PRIORITY
        );

        $eventDispatcher->addListener(
            KernelEvents::RESPONSE,
            function (ResponseEvent $event) {
                if (!$event->isMainRequest()) {
                    return;
                }
                $request = $event->getRequest();
                $previewService = $this->getFactory()->getPreviewService();
                $previewService->isPreviewAuthenticationRequested($request);
                $isInFsPreviewMode = $previewService->isPreview();

                if ($isInFsPreviewMode) {
                    $this->getLogger()->debug('[FirstSpiritPreviewEventDispatcherPlugin] In preview mode');
                    $this->addFirstSpiritResponseHeaders($event->getResponse());
                    $this->addCookies($event);
                }
            },
            static::EVENT_PRIORITY
        );

        return $eventDispatcher;
    }

    protected function addFirstSpiritResponseHeaders(Response $response): void
    {
        $this->getLogger()->debug('[FirstSpiritPreviewEventDispatcherPlugin] Adding frame-ancestors token');
        $fsWebHost = $this->getConfig()->getFsWebHost();
        $response->headers->set(self::HEADER_CONTENT_SECURITY_POLICY, 'frame-ancestors ' . $fsWebHost . ' \'self\'');
    }

    /**
     * Re-add cookies to request because the are blocked.
     */
    protected function addCookies(ResponseEvent $event): void
    {
        foreach ($event->getResponse()->headers->getCookies() as $cookie) {
            if ($cookie->isHttpOnly()) {
                $event->getResponse()->headers->setCookie(new Cookie(
                    $cookie->getName(),
                    $cookie->getValue(),
                    $cookie->getExpiresTime(),
                    $cookie->getPath(),
                    $cookie->getDomain(),
                    true,   // force secure attribute, https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie/SameSite#SameSiteNone_requires_Secure
                    $cookie->isHttpOnly(),
                    $cookie->isRaw(),
                    self::SAME_SITE_ATTRIBUTE_VALUE
                ));
            }
        }
    }
}
