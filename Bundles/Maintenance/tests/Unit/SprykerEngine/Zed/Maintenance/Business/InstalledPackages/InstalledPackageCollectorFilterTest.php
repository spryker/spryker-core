<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Maintenance\Business\InstalledPackages;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Generated\Shared\Transfer\InstalledPackageTransfer;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollector;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorFilter;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Maintenance
 * @group Business
 * @group InstalledPackageCollectorFilter
 */
class InstalledPackageCollectorFilterTest extends \PHPUnit_Framework_TestCase
{

    public function testGetInstalledPackagesShouldReturnInstalledPackageCollectionWithoutDuplicates()
    {
        $collection = new InstalledPackagesTransfer();
        $package1 = new InstalledPackageTransfer();
        $package1->setName('foo')
            ->setVersion('0.0.1')
            ->setLicense(['MIT', 'GPL'])
        ;
        $collection->addPackage($package1);

        $package2 = new InstalledPackageTransfer();
        $package2->setName('foo')
            ->setVersion('0.0.1')
            ->setLicense(['MIT', 'GPL'])
        ;
        $collection->addPackage($package2);

        $finderMock = $this->getMock('SprykerFeature\Zed\Maintenance\Business\InstalledPackages\InstalledPackageFinderInterface', ['findInstalledPackages']);
        $finderMock->expects($this->once())
            ->method('findInstalledPackages')
            ->will($this->returnValue($collection))
        ;

        $finder = [
            $finderMock,
        ];
        $collector = new InstalledPackageCollector($collection, $finder);
        $collectorFilter = new InstalledPackageCollectorFilter($collector);

        $packages = $collectorFilter->getInstalledPackages()->getPackages();

        $this->assertCount(1, $packages);
    }

}
