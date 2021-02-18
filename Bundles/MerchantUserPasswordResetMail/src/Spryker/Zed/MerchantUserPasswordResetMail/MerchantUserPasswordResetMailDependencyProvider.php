<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserPasswordResetMail;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantUserPasswordResetMail\Dependency\Facade\MerchantUserPasswordResetMailToMailFacadeBridge;
use Spryker\Zed\MerchantUserPasswordResetMail\Dependency\Facade\MerchantUserPasswordResetMailToMerchantUserFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantUserPasswordResetMail\MerchantUserPasswordResetMailConfig getConfig()
 */
class MerchantUserPasswordResetMailDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MAIL = 'FACADE_MAIL';

    public const FACADE_MERCHANT_USER = 'FACADE_MERCHANT_USER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addMailFacade($container);
        $container = $this->addMerchantUserFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMailFacade(Container $container): Container
    {
        $container->set(static::FACADE_MAIL, function (Container $container) {
            return new MerchantUserPasswordResetMailToMailFacadeBridge(
                $container->getLocator()->mail()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_USER, function (Container $container) {
            return new MerchantUserPasswordResetMailToMerchantUserFacadeBridge(
                $container->getLocator()->merchantUser()->facade()
            );
        });

        return $container;
    }
}
