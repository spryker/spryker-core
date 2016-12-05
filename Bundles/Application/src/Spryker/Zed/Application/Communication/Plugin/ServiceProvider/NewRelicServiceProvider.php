<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Service\UtilNetwork\Model\Host;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\NewRelic\NewRelicApi;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use NewRelicServiceProvider from NewRelic bundle instead
 *
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
     * @return void
     */
    public function boot(Application $app)
    {
        $app->before(function (Request $request) {
            $utilNetworkHost = new Host();
            $module = $request->attributes->get('module');
            $controller = $request->attributes->get('controller');
            $action = $request->attributes->get('action');
            $transactionName = $module . '/' . $controller . '/' . $action;

            $requestUri = array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : 'unknown';

            $host = isset($_SERVER['COMPUTERNAME']) ? $_SERVER['COMPUTERNAME'] : $utilNetworkHost->getHostname();

            $store = Store::getInstance();

            $newRelicApi = new NewRelicApi();
            $newRelicApi->setNameOfTransaction($transactionName);
            $newRelicApi->addCustomParameter('request_uri', $requestUri);
            $newRelicApi->addCustomParameter('host', $host);
            $newRelicApi->addCustomParameter('store', $store->getStoreName());
            $newRelicApi->addCustomParameter('locale', $store->getCurrentLocale());
        });
    }

}
