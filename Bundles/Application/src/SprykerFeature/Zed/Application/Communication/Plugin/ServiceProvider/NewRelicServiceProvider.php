<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Shared\Library\System;
use Spryker\Zed\Application\Communication\ApplicationDependencyContainer;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * @method ApplicationDependencyContainer getDependencyContainer()
 */
class NewRelicServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @param Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
    }

    /**
     * @param Application $app
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

            $this->getDependencyContainer()->createNewRelicApi()
                ->setNameOfTransaction($transactionName)
                ->addCustomParameter('request_uri', $requestUri)
                ->addCustomParameter('host', $host);
        });
    }

}
