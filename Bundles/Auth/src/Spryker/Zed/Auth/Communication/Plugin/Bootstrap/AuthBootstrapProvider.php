<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Communication\Plugin\Bootstrap;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Auth\AuthConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Auth\Communication\AuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\Auth\Business\AuthFacade getFacade()
 */
class AuthBootstrapProvider extends AbstractPlugin implements ServiceProviderInterface
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
        $bundleSettings = $this->getFactory()->getConfig();
        $authFacade = $this->getFacade();

        $app->before(function (Request $request) use ($app, $authFacade, $bundleSettings) {
            $bundle = $request->attributes->get('module');
            $controller = $request->attributes->get('controller');
            $action = $request->attributes->get('action');

            if ($authFacade->isIgnorable($bundle, $controller, $action)) {
                return true;
            }

            $token = null;

            if ($authFacade->hasCurrentUser()) {
                $token = $authFacade->getCurrentUserToken();
            }

            if ($request->headers->get(AuthConstants::AUTH_TOKEN)) {
                $token = $request->headers->get(AuthConstants::AUTH_TOKEN);
            }

            if ($authFacade->isStaticAuthenticated($request)) {
                return true;
            }

            if (!$authFacade->isAuthenticated($token)) {
                return $app->redirect($bundleSettings->getLoginPageUrl());
            }
        });
    }

}
