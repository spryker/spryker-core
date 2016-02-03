<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication\Plugin\Bootstrap;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Acl\Communication\AclCommunicationFactory getFactory()
 * @method \Spryker\Zed\Acl\Business\AclFacade getFacade()
 */
class AclBootstrapProvider extends AbstractPlugin implements ServiceProviderInterface
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
     * @return string
     */
    public function boot(Application $app)
    {
        $facadeAcl = $this->getFacade();
        $config = $this->getFactory()->getConfig();

        $app->before(function (Request $request) use ($app, $facadeAcl, $config) {
            $bundle = $request->attributes->get('module');
            $controller = $request->attributes->get('controller');
            $action = $request->attributes->get('action');

            if ($facadeAcl->isIgnorable($bundle, $controller, $action)) {
                return true;
            }

            if (!$facadeAcl->hasCurrentUser()) {
                return $app->redirect($config->getAccessDeniedUri());
            }

            $user = $facadeAcl->getCurrentUser();
            if (!$facadeAcl->checkAccess($user, $bundle, $controller, $action)) {
                return $app->redirect($config->getAccessDeniedUri());
            }
        });
    }

}
