<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Maintenance\Business\InstalledPackages\Composer;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\Composer\InstalledPackageFinder;

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

    public function testFindInstalledPackagesShouldReturnCollectionWithInstalledPackages()
    {
        $collection = new InstalledPackagesTransfer();
        $finder = new InstalledPackageFinder($collection, $this->getFixturesDirectory() . DIRECTORY_SEPARATOR . 'composerLock.mock');

        $this->assertInstanceOf(
            'Generated\Shared\Maintenance\InstalledPackagesInterface',
            $finder->findInstalledPackages()
        );
    }

}
