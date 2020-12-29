<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAclFacadeBridge;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToMerchantFacadeBridge;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeBridge;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserPasswordResetFacadeBridge;
use Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceBridge;

/**
 * @method \Spryker\Zed\MerchantUser\MerchantUserConfig getConfig()
 */
class MerchantUserDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_ACL = 'FACADE_ACL';
    public const FACADE_USER = 'FACADE_USER';
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';
    public const SERVICE_UTIL_TEXT = 'UTIL_TEXT_SERVICE';
    public const FACADE_USER_PASSWORD_RESET = 'FACADE_USER_PASSWORD_RESET';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        parent::provideBusinessLayerDependencies($container);

        $container = $this->addUserFacade($container);
        $container = $this->addUtilTextService($container);
        $container = $this->addAclFacade($container);
        $container = $this->addMerchantFacade($container);
        $container = $this->addUserPasswordResetFacade($container);

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
            return new MerchantUserToUserFacadeBridge(
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
    protected function addUtilTextService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container) {
            return new MerchantUserToUtilTextServiceBridge(
                $container->getLocator()->utilText()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAclFacade(Container $container): Container
    {
        $container->set(static::FACADE_ACL, function (Container $container) {
            return new MerchantUserToAclFacadeBridge(
                $container->getLocator()->acl()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT, function (Container $container) {
            return new MerchantUserToMerchantFacadeBridge(
                $container->getLocator()->merchant()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserPasswordResetFacade(Container $container): Container
    {
        $container->set(static::FACADE_USER_PASSWORD_RESET, function (Container $container) {
            return new MerchantUserToUserPasswordResetFacadeBridge(
                $container->getLocator()->userPasswordReset()->facade()
            );
        });

        return $container;
    }
}
