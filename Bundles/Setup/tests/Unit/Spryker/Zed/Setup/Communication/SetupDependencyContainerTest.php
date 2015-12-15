<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Setup\Communication;

use Spryker\Zed\Kernel\AbstractUnitTest;
use Spryker\Zed\Setup\Communication\SetupDependencyContainer;

/**
 * @method SetupDependencyContainer getDependencyContainer()
 */
class SetupDependencyContainerTest extends AbstractUnitTest
{

    /**
     * @return void
     */
    public function testCreateSetupInstallCommandNamesMustReturnArray()
    {
        $dependencyContainer = $this->getDependencyContainer();

        $this->assertInternalType('array', $dependencyContainer->createSetupInstallCommandNames());
    }

}
