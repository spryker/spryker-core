<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring\Communication\Plugin;

use Spryker\Shared\MonitoringExtension\MonitoringInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Monitoring\Communication\MonitoringCommunicationFactory getFactory()
 * @method \Spryker\Zed\Monitoring\Business\MonitoringFacadeInterface getFacade()
 */
class GatewayControllerListener extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @var \Spryker\Shared\MonitoringExtension\MonitoringInterface
     */
    protected $monitoring;

    /**
     * @param \Spryker\Shared\MonitoringExtension\MonitoringInterface $monitoring
     */
    public function __construct(MonitoringInterface $monitoring)
    {
        $this->monitoring = $monitoring;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return void
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        if ($request->attributes->has('controller') && $request->attributes->get('controller') === 'gateway') {
            $this->monitoring->addCustomParameter('Call_from', 'Yves');
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController'],
        ];
    }
}
