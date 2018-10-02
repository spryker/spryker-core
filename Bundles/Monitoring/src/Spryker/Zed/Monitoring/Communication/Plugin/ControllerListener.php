<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring\Communication\Plugin;

use Spryker\Service\Monitoring\MonitoringServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Monitoring\Dependency\Facade\MonitoringToLocaleFacadeInterface;
use Spryker\Zed\Monitoring\Dependency\Facade\MonitoringToStoreFacadeInterface;
use Spryker\Zed\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Monitoring\Communication\MonitoringCommunicationFactory getFactory()
 */
class ControllerListener extends AbstractPlugin implements EventSubscriberInterface
{
    public const PRIORITY = -255;

    /**
     * @var \Spryker\Service\Monitoring\MonitoringServiceInterface
     */
    protected $monitoringService;

    /**
     * @var \Spryker\Zed\Monitoring\Dependency\Facade\MonitoringToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Monitoring\Dependency\Facade\MonitoringToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface
     */
    protected $utilNetworkService;

    /**
     * @var array
     */
    protected $ignorableTransactions;

    /**
     * @param \Spryker\Service\Monitoring\MonitoringServiceInterface $monitoringService
     * @param \Spryker\Zed\Monitoring\Dependency\Facade\MonitoringToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Monitoring\Dependency\Facade\MonitoringToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface $utilNetworkService
     * @param array $ignorableTransactions
     */
    public function __construct(
        MonitoringServiceInterface $monitoringService,
        MonitoringToStoreFacadeInterface $storeFacade,
        MonitoringToLocaleFacadeInterface $localeFacade,
        MonitoringToUtilNetworkServiceInterface $utilNetworkService,
        array $ignorableTransactions = []
    ) {
        $this->monitoringService = $monitoringService;
        $this->storeFacade = $storeFacade;
        $this->localeFacade = $localeFacade;
        $this->utilNetworkService = $utilNetworkService;
        $this->ignorableTransactions = $ignorableTransactions;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return void
     */
    public function onKernelController(FilterControllerEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        $transactionName = $this->getTransactionName($request);
        $requestUri = $request->server->get('REQUEST_URI', 'n/a');
        $host = $request->server->get('COMPUTERNAME', $this->utilNetworkService->getHostName());

        $this->monitoringService->setTransactionName($transactionName);
        $this->monitoringService->addCustomParameter('request_uri', $requestUri);
        $this->monitoringService->addCustomParameter('host', $host);
        $this->monitoringService->addCustomParameter('store', $this->storeFacade->getStore()->getName());
        $this->monitoringService->addCustomParameter('locale', $this->localeFacade->getCurrentLocale()->getLocaleName());

        if ($this->isTransactionIgnorable($transactionName)) {
            $this->monitoringService->markIgnoreTransaction();
        }
    }

    /**
     * @param string $transaction
     *
     * @return bool
     */
    protected function isTransactionIgnorable(string $transaction): bool
    {
        foreach ($this->ignorableTransactions as $ignorableTransaction) {
            if (strpos($transaction, $ignorableTransaction) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getTransactionName(Request $request): string
    {
        $module = $request->attributes->get('module');
        $controller = $request->attributes->get('controller');
        $action = $request->attributes->get('action');
        $transactionName = $module . '/' . $controller . '/' . $action;

        return $transactionName;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', static::PRIORITY],
        ];
    }
}
