<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Application\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use SprykerEngine\Shared\Config;
use SprykerEngine\Shared\EventJournal\Model\Event;
use SprykerFeature\Client\EventJournal\Service\EventJournalClientInterface;
use SprykerFeature\Shared\NewRelic\ApiInterface;
use SprykerFeature\Shared\Library\System;
use SprykerFeature\Shared\Yves\YvesConfig;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class YvesLoggingServiceProvider implements ServiceProviderInterface
{

    /**
     * @var EventJournalClientInterface
     */
    protected $eventJournal;

    /**
     * @var ApiInterface
     */
    protected $newRelicApi;

    /**
     * @param EventJournalClientInterface $eventJournal
     * @param ApiInterface $newRelicApi
     */
    public function __construct(EventJournalClientInterface $eventJournal, ApiInterface $newRelicApi)
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
     *
     * @return void
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
     *
     * @param Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $app['dispatcher']->addListener(KernelEvents::CONTROLLER, [$this, 'onKernelController'], -255);
        $this->setDeviceIdCookie($app);
        $this->setVisitIdCookie($app);
    }

    /**
     * Handles controller requests
     *
     * @param FilterControllerEvent $event The event to handle
     *
     * @return void
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $this->logRequest($event->getRequest());
        $this->setNewRelicTransactionName($event->getRequest());
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    protected function logRequest(Request $request)
    {
        $route = $request->attributes->get('_route', 'unknown');
        // do not log the loadbalancer-heartbeat
        if (strpos($route, 'system/heartbeat') !== false) {
            return;
        }

        $this->logEvent($request);
    }

    /**
     * @param Request $request
     *
     * @return void
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

    /**
     * @param Request $request
     */
    protected function logEvent(Request $request)
    {
        $event = new Event();
        $event->setField(Event::FIELD_NAME, 'request');
        print_r( $request->attributes->get('_route'));
        if (is_string($request->attributes->get('_controller'))) {
            $event->setStaticField('controller', $request->attributes->get('_controller'));
        }
        if (is_string($request->attributes->get('_route'))) {
            $event->setStaticField('route', $request->attributes->get('_route'));
        }
        $this->eventJournal->saveEvent($event);
    }

    /**
     * @param Application $app
     *
     * @return void
     */
    private function setDeviceIdCookie(Application $app)
    {
        $this->setTrackingCookie(
            $app,
            Config::get(YvesConfig::YVES_COOKIE_DEVICE_ID_NAME),
            Config::get(YvesConfig::YVES_COOKIE_DEVICE_ID_VALID_FOR)
        );
    }

    /**
     * @param Application $app
     *
     * @return void
     */
    private function setVisitIdCookie(Application $app)
    {
        $this->setTrackingCookie(
            $app,
            Config::get(YvesConfig::YVES_COOKIE_VISITOR_ID_NAME),
            Config::get(YvesConfig::YVES_COOKIE_VISITOR_ID_VALID_FOR)
        );
    }

    /**
     * @param Application $app
     * @param $cookieName
     * @param $validFor
     *
     * @return void
     */
    private function setTrackingCookie(Application $app, $cookieName, $validFor) {
        if (empty($_COOKIE[$cookieName])) {
            $_COOKIE[$cookieName] = sha1(uniqid('', true));
        }
        $dt = new \DateTime();
        $app['cookies'][] = new Cookie(
            $cookieName,
            $_COOKIE[$cookieName],
            $dt->modify($validFor),
            '/',
            Config::get(YvesConfig::YVES_COOKIE_DOMAIN)
        );
    }
}
