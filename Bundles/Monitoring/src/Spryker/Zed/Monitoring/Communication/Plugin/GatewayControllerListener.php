<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring\Communication\Plugin;

use Spryker\Service\Monitoring\MonitoringServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Monitoring\Dependency\Facade\MonitoringToLocaleFacadeInterface;
use Spryker\Zed\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Monitoring\Communication\MonitoringCommunicationFactory getFactory()
 * @method \Spryker\Zed\Monitoring\Business\MonitoringFacadeInterface getFacade()
 * @method \Spryker\Zed\Monitoring\MonitoringConfig getConfig()
 */
class GatewayControllerListener extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @var string
     */
    protected const ATTRIBUTE_LOCALE = 'locale';

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
    protected const ATTRIBUTE_CALL_FROM = 'Call_from';

    /**
     * @var string
     */
    protected const SERVER_REQUEST_URI = 'REQUEST_URI';

    /**
     * @var string
     */
    protected const SERVER_COMPUTERNAME = 'COMPUTERNAME';

    /**
     * @var \Spryker\Service\Monitoring\MonitoringServiceInterface
     */
    protected MonitoringServiceInterface $monitoringService;

    /**
     * @var \Spryker\Zed\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface
     */
    protected MonitoringToUtilNetworkServiceInterface $utilNetworkService;

    /**
     * @var \Spryker\Zed\Monitoring\Dependency\Facade\MonitoringToLocaleFacadeInterface
     */
    protected MonitoringToLocaleFacadeInterface $localeFacade;

    /**
     * @param \Spryker\Service\Monitoring\MonitoringServiceInterface $monitoringService
     * @param \Spryker\Zed\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface $utilNetworkService
     * @param \Spryker\Zed\Monitoring\Dependency\Facade\MonitoringToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        MonitoringServiceInterface $monitoringService,
        MonitoringToUtilNetworkServiceInterface $utilNetworkService,
        MonitoringToLocaleFacadeInterface $localeFacade
    ) {
        $this->monitoringService = $monitoringService;
        $this->utilNetworkService = $utilNetworkService;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ControllerEvent $event
     *
     * @return void
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->attributes->has('controller') && $request->attributes->get('controller') === 'gateway') {
            $this->monitoringService->addCustomParameter(static::ATTRIBUTE_CALL_FROM, 'Yves');

            $requestUri = $request->server->get(static::SERVER_REQUEST_URI, 'n/a');
            $host = $request->server->get(static::SERVER_REQUEST_URI, $this->utilNetworkService->getHostName());

            $this->monitoringService->addCustomParameter(static::ATTRIBUTE_URL, $requestUri);
            $this->monitoringService->addCustomParameter(static::ATTRIBUTE_HOST, $host);
            $this->monitoringService->addCustomParameter(static::ATTRIBUTE_LOCALE, $this->localeFacade->getCurrentLocale()->getLocaleName());
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController'],
        ];
    }
}
