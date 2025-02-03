<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Monitoring\Plugin\EventDispatcher;

use Spryker\Glue\Kernel\Backend\AbstractPlugin;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Glue\Monitoring\MonitoringFactory getFactory()
 */
class MonitoringRequestTransactionEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * @var int
     */
    public const PRIORITY = -255;

    /**
     * @var string
     */
    protected const ATTRIBUTE_LOCALE = 'locale';

    /**
     * @var string
     */
    protected const ATTRIBUTE_STORE = 'store';

    /**
     * @var string
     */
    protected const ATTRIBUTE_REGION = 'region';

    /**
     * @var string
     */
    protected const ATTRIBUTE_HOST = 'host';

    /**
     * @var string
     */
    protected const ATTRIBUTE_URL = 'request_uri';

    /**
     * @var string
     */
    protected const SERVER_REQUEST_URI = 'REQUEST_URI';

    /**
     * @var string
     */
    protected const SERVER_COMPUTERNAME = 'COMPUTERNAME';

    /**
     * {@inheritDoc}
     * - Adds subscriber to listen for controller events.
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
        $eventDispatcher->addListener(KernelEvents::CONTROLLER, function (ControllerEvent $event) {
            $monitoringService = $this->getFactory()->getMonitoringService();
            $utilNetworkService = $this->getFactory()->getUtilNetworkService();

            $request = $event->getRequest();
            $transactionName = $request->attributes->get('_route') ?? 'n/a';
            $uri = $request->server->get(static::SERVER_REQUEST_URI, 'n/a');
            $host = $request->server->get(static::SERVER_COMPUTERNAME, $utilNetworkService->getHostName());

            $monitoringService->setTransactionName($transactionName);

            $monitoringService->addCustomParameter(static::ATTRIBUTE_LOCALE, $this->getLocaleName());
            $store = $this->getStore();
            if ($store !== null) {
                $monitoringService->addCustomParameter(static::ATTRIBUTE_STORE, $store);
            }
            $region = $this->getRegion();
            if ($region !== null) {
                $monitoringService->addCustomParameter(static::ATTRIBUTE_REGION, $region);
            }
            $monitoringService->addCustomParameter(static::ATTRIBUTE_HOST, $host);
            $monitoringService->addCustomParameter(static::ATTRIBUTE_URL, $uri);
        }, static::PRIORITY);

        return $eventDispatcher;
    }

    /**
     * @return string|null
     */
    protected function getStore(): ?string
    {
        if (defined('APPLICATION_STORE')) {
            return APPLICATION_STORE;
        }

        return null;
    }

    /**
     * @return string|null
     */
    protected function getRegion(): ?string
    {
        if (defined('APPLICATION_REGION')) {
            return APPLICATION_REGION;
        }

        return null;
    }

    /**
     * @return string
     */
    protected function getLocaleName(): string
    {
        if (APPLICATION === 'GLUE_BACKEND') {
            return $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleName();
        }

        return $this->getFactory()->getLocaleClient()->getCurrentLocale();
    }
}
