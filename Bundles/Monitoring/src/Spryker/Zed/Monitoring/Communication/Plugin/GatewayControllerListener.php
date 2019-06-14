<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring\Communication\Plugin;

use Spryker\Service\Monitoring\MonitoringServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Monitoring\Communication\MonitoringCommunicationFactory getFactory()
 * @method \Spryker\Zed\Monitoring\Business\MonitoringFacadeInterface getFacade()
 * @method \Spryker\Zed\Monitoring\MonitoringConfig getConfig()
 */
class GatewayControllerListener extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @var \Spryker\Service\Monitoring\MonitoringServiceInterface
     */
    protected $monitoringService;

    /**
     * @param \Spryker\Service\Monitoring\MonitoringServiceInterface $monitoringService
     */
    public function __construct(MonitoringServiceInterface $monitoringService)
    {
        $this->monitoringService = $monitoringService;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return void
     */
    public function onKernelController(FilterControllerEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->attributes->has('controller') && $request->attributes->get('controller') === 'gateway') {
            $this->monitoringService->addCustomParameter('Call_from', 'Yves');
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
