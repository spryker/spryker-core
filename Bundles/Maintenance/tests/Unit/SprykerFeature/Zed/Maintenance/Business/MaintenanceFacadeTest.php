<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Maintenance\Business;

use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Maintenance\Business\MaintenanceFacade;
use SprykerFeature\Zed\Maintenance\MaintenanceConfig;
use Symfony\Component\Filesystem\Filesystem;

class MaintenanceFacadeTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->getConfig()->getPathToFossFile());
    }

    /**
     * @return MaintenanceConfig
     */
    private function getConfig()
    {
        return new MaintenanceConfig(Config::getInstance(), $this->getLocator());
    }

    /**
     * @return MaintenanceFacade
     */
    private function getFacade()
    {
        return new MaintenanceFacade(new Factory('Maintenance'), $this->getLocator());
    }

    /**
     * @return Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

    public function testGetInstalledPackagesShouldReturnCollectionOfInstalledPackages()
    {
        $this->assertInstanceOf(
            'Generated\Shared\Maintenance\InstalledPackagesInterface',
            $this->getFacade()->getInstalledPackages()
        );
    }

    public function testWriteInstalledPackagesToMarkDownFileShouldReturnCreateMarkDownFile()
    {
        $this->assertFileNotExists($this->getConfig()->getPathToFossFile());
        $this->getFacade()->writeInstalledPackagesToMarkDownFile(
            $this->getFacade()->getInstalledPackages()
        );
        $this->assertFileExists($this->getConfig()->getPathToFossFile());
    }

}
