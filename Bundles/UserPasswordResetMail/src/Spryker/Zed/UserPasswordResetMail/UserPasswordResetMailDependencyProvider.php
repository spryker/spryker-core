<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserPasswordResetMail;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\UserPasswordResetMail\Dependency\Facade\UserPasswordResetMailToMailFacadeBridge;

/**
 * @method \Spryker\Zed\UserPasswordResetMail\UserPasswordResetMailConfig getConfig()
 */
class UserPasswordResetMailDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MAIL = 'FACADE_MAIL';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addMailFacade($container);

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
            return new UserPasswordResetMailToMailFacadeBridge(
                $container->getLocator()->mail()->facade()
            );
        });

        return $container;
    }
}
