<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Maintenance\Business\Model;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use SprykerFeature\Zed\Maintenance\Business\MaintenanceDependencyContainer;

/**
 * @group SprykerFeature
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
    private function getDependencyContainer()
    {
        return new MaintenanceDependencyContainer();
    }

    /**
     * @return void
     */
    public function testCreatePackageCollectorShouldReturnFullConfiguredInstance()
    {
        $this->assertInstanceOf(
            'SprykerFeature\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorInterface',
            $this->getDependencyContainer()->createPackageCollector()
        );
    }

    /**
     * @return void
     */
    public function testCreateMarkDownWriterShouldReturnFullConfiguredInstance()
    {
        $this->assertInstanceOf(
            'SprykerFeature\Zed\Maintenance\Business\InstalledPackages\MarkDownWriter',
            $this->getDependencyContainer()->createMarkDownWriter(new InstalledPackagesTransfer())
        );
    }

}
