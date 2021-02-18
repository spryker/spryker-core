<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserPasswordReset\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\UserPasswordReset\Business\ResetPassword\ResetPassword;
use Spryker\Zed\UserPasswordReset\Business\ResetPassword\ResetPasswordInterface;
use Spryker\Zed\UserPasswordReset\Dependency\Facade\UserPasswordResetToUserFacadeInterface;
use Spryker\Zed\UserPasswordReset\Dependency\Service\UserPasswordResetToUtilTextServiceInterface;
use Spryker\Zed\UserPasswordReset\UserPasswordResetDependencyProvider;

/**
 * @method \Spryker\Zed\UserPasswordReset\Persistence\UserPasswordResetEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\UserPasswordReset\Persistence\UserPasswordResetRepositoryInterface getRepository()
 * @method \Spryker\Zed\UserPasswordReset\UserPasswordResetConfig getConfig()
 */
class UserPasswordResetBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\UserPasswordReset\Business\ResetPassword\ResetPasswordInterface
     */
    public function createResetPassword(): ResetPasswordInterface
    {
        return new ResetPassword(
            $this->getUserFacade(),
            $this->getUtilTextService(),
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getConfig(),
            $this->getUserPasswordResetRequestStrategyPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\UserPasswordReset\Dependency\Facade\UserPasswordResetToUserFacadeInterface
     */
    public function getUserFacade(): UserPasswordResetToUserFacadeInterface
    {
        return $this->getProvidedDependency(UserPasswordResetDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\UserPasswordReset\Dependency\Service\UserPasswordResetToUtilTextServiceInterface
     */
    public function getUtilTextService(): UserPasswordResetToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(UserPasswordResetDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Zed\UserPasswordResetExtension\Dependency\Plugin\UserPasswordResetRequestStrategyPluginInterface[]
     */
    public function getUserPasswordResetRequestStrategyPlugins(): array
    {
        return $this->getProvidedDependency(UserPasswordResetDependencyProvider::PLUGINS_USER_PASSWORD_RESET_REQUEST_HANDLER);
    }
}
