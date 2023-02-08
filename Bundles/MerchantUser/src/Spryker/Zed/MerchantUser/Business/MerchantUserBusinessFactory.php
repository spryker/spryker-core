<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantUser\Business\Authenticator\MerchantUserAuthenticator;
use Spryker\Zed\MerchantUser\Business\Authenticator\MerchantUserAuthenticatorInterface;
use Spryker\Zed\MerchantUser\Business\Creator\MerchantUserCreator;
use Spryker\Zed\MerchantUser\Business\Creator\MerchantUserCreatorInterface;
use Spryker\Zed\MerchantUser\Business\Deleter\MerchantUserDeleter;
use Spryker\Zed\MerchantUser\Business\Deleter\MerchantUserDeleterInterface;
use Spryker\Zed\MerchantUser\Business\Reader\CurrentMerchantUserReader;
use Spryker\Zed\MerchantUser\Business\Reader\CurrentMerchantUserReaderInterface;
use Spryker\Zed\MerchantUser\Business\Reader\MerchantUserReader;
use Spryker\Zed\MerchantUser\Business\Reader\MerchantUserReaderInterface;
use Spryker\Zed\MerchantUser\Business\Reader\UserReader;
use Spryker\Zed\MerchantUser\Business\Reader\UserReaderInterface;
use Spryker\Zed\MerchantUser\Business\Updater\MerchantUserUpdater;
use Spryker\Zed\MerchantUser\Business\Updater\MerchantUserUpdaterInterface;
use Spryker\Zed\MerchantUser\Business\UserRoleFilter\BackofficeMerchantUserRoleFilter;
use Spryker\Zed\MerchantUser\Business\UserRoleFilter\BackofficeMerchantUserRoleFilterInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToMerchantFacadeInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserPasswordResetFacadeInterface;
use Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceInterface;
use Spryker\Zed\MerchantUser\MerchantUserDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantUser\MerchantUserConfig getConfig()
 * @method \Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface getRepository()
 */
class MerchantUserBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantUser\Business\Creator\MerchantUserCreatorInterface
     */
    public function createMerchantUserCreator(): MerchantUserCreatorInterface
    {
        return new MerchantUserCreator(
            $this->getUtilTextService(),
            $this->getUserFacade(),
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getConfig(),
            $this->getMerchantUserPostCreatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\Updater\MerchantUserUpdaterInterface
     */
    public function createMerchantUserUpdater(): MerchantUserUpdaterInterface
    {
        return new MerchantUserUpdater(
            $this->getUserFacade(),
            $this->getUserPasswordResetFacade(),
            $this->getRepository(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\Deleter\MerchantUserDeleterInterface
     */
    public function createMerchantUserDeleter(): MerchantUserDeleterInterface
    {
        return new MerchantUserDeleter(
            $this->getUserFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\Reader\MerchantUserReaderInterface
     */
    public function createMerchantUserReader(): MerchantUserReaderInterface
    {
        return new MerchantUserReader(
            $this->getRepository(),
            $this->getUserFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\Reader\CurrentMerchantUserReaderInterface
     */
    public function createCurrentMerchantUserReader(): CurrentMerchantUserReaderInterface
    {
        return new CurrentMerchantUserReader(
            $this->getUserFacade(),
            $this->getRepository(),
            $this->getMerchantFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\UserRoleFilter\BackofficeMerchantUserRoleFilterInterface
     */
    public function createBackofficeMerchantUserRoleFilter(): BackofficeMerchantUserRoleFilterInterface
    {
        return new BackofficeMerchantUserRoleFilter(
            $this->getConfig(),
            $this->getRepository(),
            $this->getMerchantUserRoleFilterPreConditionPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\Authenticator\MerchantUserAuthenticatorInterface
     */
    public function createMerchantUserAuthenticator(): MerchantUserAuthenticatorInterface
    {
        return new MerchantUserAuthenticator($this->getUserFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\Reader\UserReaderInterface
     */
    public function createUserReader(): UserReaderInterface
    {
        return new UserReader($this->getUserFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    public function getUserFacade(): MerchantUserToUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantUserDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceInterface
     */
    public function getUtilTextService(): MerchantUserToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(MerchantUserDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserPasswordResetFacadeInterface
     */
    public function getUserPasswordResetFacade(): MerchantUserToUserPasswordResetFacadeInterface
    {
        return $this->getProvidedDependency(MerchantUserDependencyProvider::FACADE_USER_PASSWORD_RESET);
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantUserToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantUserDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return array<\Spryker\Zed\MerchantUserExtension\Dependency\Plugin\MerchantUserPostCreatePluginInterface>
     */
    public function getMerchantUserPostCreatePlugins(): array
    {
        return $this->getProvidedDependency(MerchantUserDependencyProvider::PLUGINS_MERCHANT_USER_POST_CREATE);
    }

    /**
     * @return array<\Spryker\Zed\MerchantUserExtension\Dependency\Plugin\MerchantUserRoleFilterPreConditionPluginInterface>
     */
    public function getMerchantUserRoleFilterPreConditionPlugins(): array
    {
        return $this->getProvidedDependency(MerchantUserDependencyProvider::PLUGINS_MERCHANT_USER_ROLE_FILTER_PRE_CONDITION);
    }
}
