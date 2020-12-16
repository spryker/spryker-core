<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityOauthUser;

use Codeception\Actor;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SecurityOauthUser\SecurityOauthUserDependencyProvider;
use Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserClientStrategyPluginInterface;

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
class SecurityOauthUserCommunicationTester extends Actor
{
    use _generated\SecurityOauthUserCommunicationTesterActions;

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory|\Spryker\Zed\SecurityGui\Communication\SecurityOauthUserCommunicationFactory
     */
    public function getCommunicationFactory(): AbstractCommunicationFactory
    {
        $factory = $this->getFactory();

        $this->mockConfigMethod('getIgnorablePaths', function () {
            return '/ignorable';
        });

        $factory->setConfig($this->getModuleConfig('SecurityOauthUser'));

        return $factory;
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
}
