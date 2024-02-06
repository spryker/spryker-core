<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AgentSecurityMerchantPortalGui;

use Codeception\Actor;
use ReflectionClass;
use Spryker\Zed\Security\Communication\Configurator\SecurityConfigurator;

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
 * @SuppressWarnings(\SprykerTest\Zed\SecurityMerchantPortalGui\PHPMD)
 */
class AgentSecurityMerchantPortalGuiCommunicationTester extends Actor
{
    use _generated\AgentSecurityMerchantPortalGuiCommunicationTesterActions;

    /**
     * @return void
     */
    public function resetSecurityConfiguration(): void
    {
        $reflection = new ReflectionClass(SecurityConfigurator::class);
        $property = $reflection->getProperty('securityConfiguration');
        $property->setAccessible(true);
        $property->setValue(null);
    }
}
