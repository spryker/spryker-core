<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Maintenance\Business\Model;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Maintenance\Business\MaintenanceDependencyContainer;
use SprykerFeature\Zed\Maintenance\MaintenanceConfig;

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
        $factory = new Factory('Maintenance');
        $config = new MaintenanceConfig(Config::getInstance(), Locator::getInstance());

        return new MaintenanceDependencyContainer($factory, Locator::getInstance(), $config);
    }

    public function testCreatePackageCollectorShouldReturnFullConfiguredInstance()
    {
        $this->assertInstanceOf(
            'SprykerFeature\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorInterface',
            $this->getDependencyContainer()->createPackageCollector()
        );
    }

    public function testCreateMarkDownWriterShouldReturnFullConfiguredInstance()
    {
        $this->assertInstanceOf(
            'SprykerFeature\Zed\Maintenance\Business\InstalledPackages\MarkDownWriter',
            $this->getDependencyContainer()->createMarkDownWriter(new InstalledPackagesTransfer())
        );
    }

}
