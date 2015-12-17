<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication\Plugin\Bootstrap;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Acl\Business\AclFacade;
use Spryker\Zed\Acl\Communication\AclCommunicationFactory;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method AclCommunicationFactory getFactory()
 * @method AclFacade getFacade()
 */
class AclBootstrapProvider extends AbstractPlugin implements ServiceProviderInterface
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
     * @return string
     */
    public function boot(Application $app)
    {
        $facadeAcl = $this->getFacade();
        $facadeUser = $this->getFactory()->createUserFacade();
        $config = $this->getFactory()->getConfig();

        $app->before(function (Request $request) use ($app, $facadeAcl, $facadeUser, $config) {
            $bundle = $request->attributes->get('module');
            $controller = $request->attributes->get('controller');
            $action = $request->attributes->get('action');

            if ($facadeAcl->isIgnorable($bundle, $controller, $action)) {
                return true;
            }

            if (!$facadeUser->hasCurrentUser()) {
                return $app->redirect($config->getAccessDeniedUri());
            }

            $user = $facadeUser->getCurrentUser();
            if (!$facadeAcl->checkAccess($user, $bundle, $controller, $action)) {
                return $app->redirect($config->getAccessDeniedUri());
            }
        });
    }

}
