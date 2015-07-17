<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Communication\Plugin\Bootstrap;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Silex\Application;
use Silex\ServiceProviderInterface;
use SprykerFeature\Zed\Auth\Communication\AuthDependencyContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method AuthDependencyContainer getDependencyContainer()
 */
class AuthBootstrapProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {

    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $settings = $this->getDependencyContainer()->getConfig();
        $facade = $this->getDependencyContainer()->locateAuthFacade();
        $userFacade = $this->getDependencyContainer()->locateUserFacade();

        $app->before(function (Request $request) use ($app, $facade, $userFacade, $settings) {
            $bundle = $request->attributes->get('module');
            $controller = $request->attributes->get('controller');
            $action = $request->attributes->get('action');

            if ($facade->isIgnorable($bundle, $controller, $action)) {
                return true;
            }

            $token = null;

            if ($facade->hasCurrentUser()) {
                $token = $facade->getCurrentUserToken();
            }

            if ($request->headers->get('Auth-Token')) {
                $token = $request->headers->get('Auth-Token');
            }

            if (!$facade->isAuthorized($token)) {
                return $app->redirect($settings->getLoginPageUrl());
            }
        });
    }

}
