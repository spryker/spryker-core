<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Monitoring\Plugin;

use Spryker\Shared\MonitoringExtension\MonitoringInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Yves\Monitoring\MonitoringFactory getFactory()
 */
class ControllerListener extends AbstractPlugin implements EventSubscriberInterface
{
    const PRIORITY = -255;

    /**
     * @var \Spryker\Shared\MonitoringExtension\MonitoringInterface
     */
    protected $monitoring;

    /**
     * @var \Spryker\Yves\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface
     */
    protected $utilNetworkService;

    /**
     * @var array
     */
    protected $ignorableTransactions;

    /**
     * @param \Spryker\Shared\MonitoringExtension\MonitoringInterface $monitoring
     * @param \Spryker\Yves\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface $utilNetworkService
     * @param array $ignorableTransactions
     */
    public function __construct(MonitoringInterface $monitoring, MonitoringToUtilNetworkServiceInterface $utilNetworkService, array $ignorableTransactions = [])
    {
        $this->monitoring = $monitoring;
        $this->utilNetworkService = $utilNetworkService;
        $this->ignorableTransactions = $ignorableTransactions;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return void
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        $transactionName = $request->attributes->get('_route');
        $requestUri = $request->server->get('REQUEST_URI', 'n/a');
        $host = $request->server->get('COMPUTERNAME', $this->utilNetworkService->getHostName());

        $this->monitoring->setTransactionName($transactionName);
        $this->monitoring->addCustomParameter('request_uri', $requestUri);
        $this->monitoring->addCustomParameter('host', $host);

        if ($this->ignoreTransaction($transactionName)) {
            $this->monitoring->markIgnoreTransaction();
        }
    }

    /**
     * @param string $transaction
     *
     * @return bool
     */
    protected function ignoreTransaction($transaction)
    {
        foreach ($this->ignorableTransactions as $ignorableTransaction) {
            if (strpos($transaction, $ignorableTransaction) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', static::PRIORITY],
        ];
    }
}
