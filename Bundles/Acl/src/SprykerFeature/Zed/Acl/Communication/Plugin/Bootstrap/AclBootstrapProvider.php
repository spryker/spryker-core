<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Plugin\Bootstrap;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Silex\Application;
use Silex\ServiceProviderInterface;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Acl\Business\AclFacade;
use SprykerFeature\Zed\Acl\Communication\AclDependencyContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method AclDependencyContainer getDependencyContainer()
 * @method AclFacade getFacade()
 */
class AclBootstrapProvider extends AbstractPlugin implements ServiceProviderInterface
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
     * @return string
     */
    public function boot(Application $app)
    {
        $aclFacade = $this->getFacade();
        $userFacade = Locator::getInstance()->user()->facade();
        $settings = $this->getDependencyContainer()->getConfig();

        $app->before(function (Request $request) use ($app, $aclFacade, $userFacade, $settings) {
            $bundle = $request->attributes->get('module');
            $controller = $request->attributes->get('controller');
            $action = $request->attributes->get('action');

            if ($aclFacade->isIgnorable($bundle, $controller, $action)) {
                return true;
            }

            if (!$userFacade->hasCurrentUser()) {
                return $app->redirect($settings->getAccessDeniedUri());
            }

            $user = $userFacade->getCurrentUser();
            if (!$aclFacade->checkAccess($user, $bundle, $controller, $action)) {
                return $app->redirect($settings->getAccessDeniedUri());
            }
        });
    }

}
