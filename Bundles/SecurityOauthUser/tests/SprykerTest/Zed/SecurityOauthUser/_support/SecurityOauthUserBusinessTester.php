<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityOauthUser;

use Codeception\Actor;
use Spryker\Zed\SecurityOauthUser\Business\SecurityOauthUserBusinessFactory;
use Spryker\Zed\SecurityOauthUser\Business\SecurityOauthUserFacadeInterface;
use Spryker\Zed\SecurityOauthUser\SecurityOauthUserDependencyProvider;
use Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserClientStrategyPluginInterface;
use Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserRestrictionPluginInterface;
use Spryker\Zed\User\Business\UserFacadeInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class SecurityOauthUserBusinessTester extends Actor
{
    use _generated\SecurityOauthUserBusinessTesterActions;

    /**
     * @return \Spryker\Zed\SecurityOauthUser\Business\SecurityOauthUserFacadeInterface
     */
    public function getSecurityOauthUserFacade(): SecurityOauthUserFacadeInterface
    {
        return $this->getLocator()->securityOauthUser()->facade();
    }

    /**
     * @return \Spryker\Zed\User\Business\UserFacadeInterface
     */
    public function getUserFacade(): UserFacadeInterface
    {
        return $this->getLocator()->user()->facade();
    }

    /**
     * @param \Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserClientStrategyPluginInterface $oauthUserClientStrategyPlugin
     *
     * @return void
     */
    public function setOauthUserClientStrategyPlugin(
        OauthUserClientStrategyPluginInterface $oauthUserClientStrategyPlugin
    ): void {
        $this->setDependency(SecurityOauthUserDependencyProvider::PLUGINS_OAUTH_USER_CLIENT_STRATEGY, [
            $oauthUserClientStrategyPlugin,
        ]);
    }

    /**
     * @param \Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserRestrictionPluginInterface $oauthUserRestrictionPlugin
     *
     * @return void
     */
    public function setOauthUserRestrictionPlugin(
        OauthUserRestrictionPluginInterface $oauthUserRestrictionPlugin
    ): void {
        $this->setDependency(SecurityOauthUserDependencyProvider::PLUGINS_OAUTH_USER_RESTRICTION, [
            $oauthUserRestrictionPlugin,
        ]);
    }

    /**
     * @param string $authenticationStrategy
     * @param string|null $groupReference
     *
     * @return \Spryker\Zed\SecurityOauthUser\Business\SecurityOauthUserFacadeInterface
     */
    public function mockSecurityOauthUserFacade(
        string $authenticationStrategy,
        ?string $groupReference = null
    ): SecurityOauthUserFacadeInterface {
        $this->mockConfigMethod('getAuthenticationStrategy', function () use ($authenticationStrategy) {
            return $authenticationStrategy;
        });

        $mockConfig = $this->mockConfigMethod('getOauthUserGroupReference', function () use ($groupReference) {
            return $groupReference;
        });

        $securityOauthUserBusinessFactory = (new SecurityOauthUserBusinessFactory())
            ->setConfig($mockConfig);

        return $this->getSecurityOauthUserFacade()->setFactory($securityOauthUserBusinessFactory);
    }
}
