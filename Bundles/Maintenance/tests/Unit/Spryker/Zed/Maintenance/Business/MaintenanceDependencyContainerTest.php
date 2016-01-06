<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Maintenance\Business\Model;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Spryker\Zed\Maintenance\Business\MaintenanceBusinessFactory;

/**
 * @group Spryker
 * @group Zed
 * @group Maintenance
 * @group Business
 * @group MaintenanceBusinessFactory
 */
class MaintenanceDependencyContainerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return MaintenanceBusinessFactory
     */
    private function getFactory()
    {
        return new MaintenanceBusinessFactory();
    }

    /**
     * @return void
     */
    public function testCreatePackageCollectorShouldReturnFullConfiguredInstance()
    {
        $this->assertInstanceOf(
            'Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorInterface',
            $this->getFactory()->createPackageCollector()
        );
    }

    /**
     * @return void
     */
    public function testCreateMarkDownWriterShouldReturnFullConfiguredInstance()
    {
        $this->assertInstanceOf(
            'Spryker\Zed\Maintenance\Business\InstalledPackages\MarkDownWriter',
            $this->getFactory()->createMarkDownWriter(new InstalledPackagesTransfer())
        );
    }

}
