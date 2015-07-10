<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Maintenance\Business\InstalledPackages;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollector;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Maintenance
 * @group Business
 * @group InstalledPackageCollector
 */
class InstalledPackageCollectorTest extends \PHPUnit_Framework_TestCase
{

    public function testGetInstalledPackagesShouldReturnInstalledPackageCollection()
    {

        $collection = new InstalledPackagesTransfer();
        $finder = [
            $this->getMock('SprykerFeature\Zed\Maintenance\Business\InstalledPackages\InstalledPackageFinderInterface', ['findInstalledPackages']),
        ];
        $collector = new InstalledPackageCollector($collection, $finder);

        $this->assertInstanceOf(
            'Generated\Shared\Maintenance\InstalledPackagesInterface',
            $collector->getInstalledPackages()
        );
    }

}
