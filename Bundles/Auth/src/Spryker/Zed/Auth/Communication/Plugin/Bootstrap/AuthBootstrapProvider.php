<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Communication\Plugin\Bootstrap;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Auth\AuthConfig;
use Spryker\Zed\Auth\Business\AuthFacade;
use Spryker\Zed\Auth\Communication\AuthCommunicationFactory;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method AuthCommunicationFactory getFactory()
 * @method AuthFacade getFacade()
 */
class AuthBootstrapProvider extends AbstractPlugin implements ServiceProviderInterface
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
     * @return void
     */
    public function boot(Application $app)
    {
        $bundleSettings = $this->getFactory()->getConfig();
        $facadeAuth = $this->getFacade();

        $app->before(function (Request $request) use ($app, $facadeAuth, $bundleSettings) {
            $bundle = $request->attributes->get('module');
            $controller = $request->attributes->get('controller');
            $action = $request->attributes->get('action');

            if ($facadeAuth->isIgnorable($bundle, $controller, $action)) {
                return true;
            }

            $token = null;

            if ($facadeAuth->hasCurrentUser()) {
                $token = $facadeAuth->getCurrentUserToken();
            }

            if ($request->headers->get(AuthConfig::AUTH_TOKEN)) {
                $token = $request->headers->get(AuthConfig::AUTH_TOKEN);
            }

            if (!$facadeAuth->isAuthenticated($token)) {
                return $app->redirect($bundleSettings->getLoginPageUrl());
            }
        });
    }

}
