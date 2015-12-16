<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Maintenance\Business\Model;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Spryker\Zed\Maintenance\Business\MaintenanceDependencyContainer;

/**
 * @group Spryker
 * @group Zed
 * @group Maintenance
 * @group Business
 * @group MaintenanceDependencyContainer
 */
class MaintenanceDependencyContainerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return MaintenanceDependencyContainer
     */
    private function getBusinessFactory()
    {
        return new MaintenanceDependencyContainer();
    }

    /**
     * @return void
     */
    public function testCreatePackageCollectorShouldReturnFullConfiguredInstance()
    {
        $this->assertInstanceOf(
            'Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorInterface',
            $this->getBusinessFactory()->createPackageCollector()
        );
    }

    /**
     * @return void
     */
    public function testCreateMarkDownWriterShouldReturnFullConfiguredInstance()
    {
        $this->assertInstanceOf(
            'Spryker\Zed\Maintenance\Business\InstalledPackages\MarkDownWriter',
            $this->getBusinessFactory()->createMarkDownWriter(new InstalledPackagesTransfer())
        );
    }

}
