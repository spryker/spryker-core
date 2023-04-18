<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Http\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Yves\Http\HttpConfig getConfig()
 * @method \Spryker\Yves\Http\HttpFactory getFactory()
 */
class EnvironmentInfoHeaderEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * @var string
     */
    protected const HEADER_X_CODE_BUCKET_NAME = 'X-CodeBucket';

    /**
     * @var string
     */
    protected const HEADER_X_STORE_NAME = 'X-Store';

    /**
     * @var string
     */
    protected const HEADER_X_ENV_NAME = 'X-Env';

    /**
     * @var string
     */
    protected const HEADER_X_LOCALE_NAME = 'X-Locale';

    /**
     * {@inheritDoc}
     * - Sets store main information in headers.
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
        $eventDispatcher->addListener(KernelEvents::RESPONSE, function (ResponseEvent $event): void {
            $this->onKernelResponse($event);
        });

        return $eventDispatcher;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
     *
     * @return bool
     */
    protected function isMainRequest(ResponseEvent $event): bool
    {
        if (method_exists($event, 'isMasterRequest')) {
            return $event->isMasterRequest();
        }

        return $event->isMainRequest();
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
     *
     * @return void
     */
    protected function onKernelResponse(ResponseEvent $event): void
    {
        if (!$this->isMainRequest($event)) {
            return;
        }

        $response = $event->getResponse();

        $localeClient = $this->getFactory()->getLocaleClient();
        $storeClient = $this->getFactory()->getStoreClient();

        $response->headers->set(static::HEADER_X_CODE_BUCKET_NAME, APPLICATION_CODE_BUCKET);
        $response->headers->set(static::HEADER_X_STORE_NAME, $storeClient->getCurrentStore()->getNameOrFail());
        $response->headers->set(static::HEADER_X_ENV_NAME, APPLICATION_ENV);
        $response->headers->set(static::HEADER_X_LOCALE_NAME, $localeClient->getCurrentLocale());
    }
}
