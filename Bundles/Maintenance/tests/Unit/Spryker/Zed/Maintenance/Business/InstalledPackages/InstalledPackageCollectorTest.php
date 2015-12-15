<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Maintenance\Business\InstalledPackages;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollector;

/**
 * @group Spryker
 * @group Zed
 * @group Maintenance
 * @group Business
 * @group InstalledPackageCollector
 */
class InstalledPackageCollectorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetInstalledPackagesShouldReturnInstalledPackageCollection()
    {
        $collection = new InstalledPackagesTransfer();
        $finder = [
            $this->getMock('Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageFinderInterface', ['findInstalledPackages']),
        ];
        $collector = new InstalledPackageCollector($collection, $finder);

        $this->assertInstanceOf(
            'Generated\Shared\Transfer\InstalledPackagesTransfer',
            $collector->getInstalledPackages()
        );
    }

}
