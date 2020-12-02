<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SecurityGui\Dependency\Facade\SecurityGuiToMessengerFacadeBridge;
use Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToMessengerFacadeBridge;
use Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeBridge;

/**
 * @method \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig getConfig()
 */
class SecurityOauthUserDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_USER = 'FACADE_USER';
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addUserFacade($container);
        $container = $this->addMessengerFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_USER, function (Container $container) {
            return new SecurityOauthUserToUserFacadeBridge(
                $container->getLocator()->user()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container): Container
    {
        $container->set(static::FACADE_MESSENGER, function (Container $container) {
            return new SecurityOauthUserToMessengerFacadeBridge(
                $container->getLocator()->messenger()->facade()
            );
        });

        return $container;
    }
}
