<?php

namespace Unit\SprykerEngine\Zed\Maintenance\Business\InstalledPackages\Composer;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\Composer\InstalledPackageFinder;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollector;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Maintenance
 * @group Business
 * @group InstalledPackageFinder
 */
class InstalledPackageFinderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return string
     */
    private function getFixturesDirectory()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures';
    }

    public function testGetInstalledPackagesShouldReturnInstalledPackageCollection()
    {
        $collection = new InstalledPackagesTransfer();
        $finder = new InstalledPackageFinder($collection, $this->getFixturesDirectory() . DIRECTORY_SEPARATOR . 'composerLock.mock');

        $this->assertInstanceOf(
            'Generated\Shared\Maintenance\InstalledPackagesInterface',
            $finder->findInstalledPackages()
        );
    }

}
