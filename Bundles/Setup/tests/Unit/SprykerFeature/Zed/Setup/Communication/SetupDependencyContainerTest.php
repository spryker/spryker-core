<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Setup\Communication;

use SprykerEngine\Zed\Kernel\AbstractUnitTest;
use SprykerFeature\Zed\Setup\Communication\SetupDependencyContainer;

/**
 * @method SetupDependencyContainer getDependencyContainer()
 */
class SetupDependencyContainerTest extends AbstractUnitTest
{

    public function testCreateSetupInstallCommandNamesMustReturnArray()
    {
        $dependencyContainer = $this->getDependencyContainer();

        $this->assertInternalType('array', $dependencyContainer->createSetupInstallCommandNames());
    }
}
