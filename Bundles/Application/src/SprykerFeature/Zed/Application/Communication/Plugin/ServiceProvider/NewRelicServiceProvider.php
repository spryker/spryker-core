<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\NewRelic\Api;
use SprykerFeature\Shared\Library\System;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Silex\ServiceProviderInterface;

class NewRelicServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
    }

    /**
     * @param Application $app
     *
     * @throws \Exception
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

            Api::getInstance()
                ->setNameOfTransaction($transactionName)
                ->addCustomParameter('request_uri', $requestUri)
                ->addCustomParameter('host', $host);
        });
    }

}
