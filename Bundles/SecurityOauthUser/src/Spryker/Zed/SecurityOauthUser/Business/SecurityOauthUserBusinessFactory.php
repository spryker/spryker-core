<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SecurityOauthUser\Business\Checker\OauthUserRestrictionChecker;
use Spryker\Zed\SecurityOauthUser\Business\Checker\OauthUserRestrictionCheckerInterface;
use Spryker\Zed\SecurityOauthUser\Business\Reader\ResourceOwnerReader;
use Spryker\Zed\SecurityOauthUser\Business\Reader\ResourceOwnerReaderInterface;
use Spryker\Zed\SecurityOauthUser\Business\Resolver\CreateUserAuthenticationStrategy;
use Spryker\Zed\SecurityOauthUser\Business\Resolver\AuthenticationStrategyResolver;
use Spryker\Zed\SecurityOauthUser\Business\Resolver\AuthenticationStrategyResolverInterface;
use Spryker\Zed\SecurityOauthUser\Business\Resolver\ExistingUserAuthenticationStrategy;
use Spryker\Zed\SecurityOauthUser\Business\Strategy\AuthenticationStrategyInterface;
use Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface;
use Spryker\Zed\SecurityOauthUser\Dependency\Service\SecurityOauthUserToUtilTextServiceInterface;
use Spryker\Zed\SecurityOauthUser\SecurityOauthUserDependencyProvider;

/**
 * @method \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig getConfig()
 */
class SecurityOauthUserBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SecurityOauthUser\Business\Reader\ResourceOwnerReaderInterface
     */
    public function createResourceOwnerReader(): ResourceOwnerReaderInterface
    {
        return new ResourceOwnerReader($this->getOauthUserClientStrategyPlugins());
    }

    /**
     * @return \Spryker\Zed\SecurityOauthUser\Business\Checker\OauthUserRestrictionCheckerInterface
     */
    public function createOauthUserRestrictionChecker(): OauthUserRestrictionCheckerInterface
    {
        return new OauthUserRestrictionChecker($this->getOauthUserRestrictionPlugins());
    }

    /**
     * @return \Spryker\Zed\SecurityOauthUser\Business\Resolver\AuthenticationStrategyResolverInterface
     */
    public function createAuthenticationStrategyResolver(): AuthenticationStrategyResolverInterface
    {
        return new AuthenticationStrategyResolver($this->getConfig(),
            [
                $this->createExistingUserAuthenticationStrategy(),
                $this->createCreateUserAuthenticationStrategy()
            ]
        );
    }

    /**
     * @return \Spryker\Zed\SecurityOauthUser\Business\Strategy\AuthenticationStrategyInterface
     */
    public function createCreateUserAuthenticationStrategy(): AuthenticationStrategyInterface
    {
        return new CreateUserAuthenticationStrategy(
            $this->getConfig(),
            $this->getUserFacade(),
            $this->getUtilTextService()
        );
    }

    /**
     * @return \Spryker\Zed\SecurityOauthUser\Business\Strategy\AuthenticationStrategyInterface
     */
    public function createExistingUserAuthenticationStrategy(): AuthenticationStrategyInterface
    {
        return new ExistingUserAuthenticationStrategy($this->getUserFacade());
    }

    /**
     * @return \Spryker\Zed\SecurityOauthUser\Dependency\Service\SecurityOauthUserToUtilTextServiceInterface
     */
    public function getUtilTextService(): SecurityOauthUserToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(SecurityOauthUserDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface
     */
    public function getUserFacade(): SecurityOauthUserToUserFacadeInterface
    {
        return $this->getProvidedDependency(SecurityOauthUserDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserClientStrategyPluginInterface[]
     */
    public function getOauthUserClientStrategyPlugins(): array
    {
        return $this->getProvidedDependency(SecurityOauthUserDependencyProvider::PLUGINS_OAUTH_USER_CLIENT_STRATEGY);
    }

    /**
     * @return \Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserRestrictionPluginInterface[]
     */
    public function getOauthUserRestrictionPlugins(): array
    {
        return $this->getProvidedDependency(SecurityOauthUserDependencyProvider::PLUGINS_OAUTH_USER_RESTRICTION);
    }
}
