<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Plugin\Bootstrap;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use `\Spryker\Zed\Acl\Communication\Plugin\EventDispatcher\AccessControlEventDispatcherPlugin` instead.
 *
 * @method \Spryker\Zed\Acl\Communication\AclCommunicationFactory getFactory()
 * @method \Spryker\Zed\Acl\Business\AclFacadeInterface getFacade()
 * @method \Spryker\Zed\Acl\AclConfig getConfig()
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface getQueryContainer()
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
     * @return void
     */
    public function boot(Application $app)
    {
        $aclFacade = $this->getFacade();
        $config = $this->getFactory()->getConfig();

        $app->before(function (Request $request) use ($app, $aclFacade, $config) {
            $bundle = $request->attributes->get('module');
            $controller = $request->attributes->get('controller');
            $action = $request->attributes->get('action');

            if ($aclFacade->isIgnorable($bundle, $controller, $action)) {
                return true;
            }

            if (!$aclFacade->hasCurrentUser()) {
                return $app->redirect($config->getAccessDeniedUri());
            }

            $user = $aclFacade->getCurrentUser();
            if (!$aclFacade->checkAccess($user, $bundle, $controller, $action)) {
                return $app->redirect($config->getAccessDeniedUri());
            }
        });
    }
}
