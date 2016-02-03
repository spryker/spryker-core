<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Shared\Library\System;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 * @method \Spryker\Zed\Application\Business\ApplicationFacade getFacade()
 */
class NewRelicServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @throws \Exception
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $app->before(function (Request $request) {
            $module = $request->attributes->get('module');
            $controller = $request->attributes->get('controller');
            $action = $request->attributes->get('action');
            $transactionName = $module . '/' . $controller . '/' . $action;

            $requestUri = array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : 'unknown';

            $host = isset($_SERVER['COMPUTERNAME']) ? $_SERVER['COMPUTERNAME'] : System::getHostname();

            $store = Store::getInstance();

            $this->getFactory()->createNewRelicApi()
                ->setNameOfTransaction($transactionName)
                ->addCustomParameter('request_uri', $requestUri)
                ->addCustomParameter('host', $host)
                ->addCustomParameter('store', $store->getStoreName())
                ->addCustomParameter('locale', $store->getCurrentLocale());
        });
    }

}
