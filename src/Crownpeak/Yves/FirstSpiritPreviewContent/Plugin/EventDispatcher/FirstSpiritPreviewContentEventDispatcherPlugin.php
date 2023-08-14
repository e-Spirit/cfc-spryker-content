<?php

namespace Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentFactory getFactory()
 * @method \Crownpeak\Yves\FirstSpiritPreviewContent\FirstSpiritPreviewContentConfig getConfig()
 */
class FirstSpiritPreviewContentEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
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

        return $eventDispatcher;
    }
}
