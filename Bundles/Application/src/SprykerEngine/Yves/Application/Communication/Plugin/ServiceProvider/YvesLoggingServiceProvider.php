<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use SprykerEngine\Shared\Lumberjack\Model\Event;
use SprykerEngine\Shared\Lumberjack\Model\EventJournalInterface;
use SprykerFeature\Shared\NewRelic\ApiInterface;
use SprykerFeature\Shared\Library\System;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class YvesLoggingServiceProvider implements ServiceProviderInterface
{

    /**
     * @var EventJournalInterface
     */
    protected $eventJournal;

    /**
     * @var ApiInterface
     */
    protected $newRelicApi;

    /**
     * @param EventJournalInterface $eventJournal
     * @param ApiInterface $newRelicApi
     */
    public function __construct(EventJournalInterface $eventJournal, ApiInterface $newRelicApi)
    {
        $this->eventJournal = $eventJournal;
        $this->newRelicApi = $newRelicApi;
    }

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
    }

    /**
     * Handles controller requests
     *
     * @param FilterControllerEvent $event The event to handle
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $this->logRequest($event->getRequest());
        $this->setNewRelicTransactionName($event->getRequest());
    }

    /**
     * @param Request $request
     */
    protected function logRequest(Request $request)
    {
        $route = $request->attributes->get('_route', 'unknown');

        // do not log the loadbalancer-heartbeat
        if (strpos($route, 'system/heartbeat') !== false) {
            return;
        }

        $event = new Event();
        $event->addField(Event::FIELD_NAME, 'request');
        $this->eventJournal->saveEvent($event);
    }

    /**
     * @param Request $request
     */
    protected function setNewRelicTransactionName(Request $request)
    {
        $transactionName = $request->attributes->get('_route');
        $host = $request->server->get('COMPUTERNAME', System::getHostname());
        $requestUri = $request->getRequestUri();

        $this->newRelicApi->setNameOfTransaction($transactionName)
            ->addCustomParameter('request_uri', $requestUri)
            ->addCustomParameter('host', $host);

        if (strpos($transactionName, 'system/heartbeat') !== false) {
            $this->newRelicApi->markIgnoreTransaction();
        }
    }

}
