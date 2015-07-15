<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Maintenance\Business\InstalledPackages\NodePackageManager;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\NodePackageManager\InstalledPackageFinder;
use Symfony\Component\Process\Process;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Maintenance
 * @group Business
 * @group InstalledPackageFinder
 */
class InstalledPackageFinderTest extends \PHPUnit_Framework_TestCase
{

    public function testFindInstalledPackagesShouldReturnCollectionWithInstalledPackage()
    {
        $collection = new InstalledPackagesTransfer();
        $finder = new InstalledPackageFinder($collection, $this->getProcessMockWithSuccessFullResponse(), '');

        $this->assertInstanceOf(
            'Generated\Shared\Maintenance\InstalledPackagesInterface',
            $finder->findInstalledPackages()
        );
    }

    public function testFindInstalledPackagesShouldThrowExceptionIfProcessWasNotSuccessful()
    {
        $this->setExpectedException('\RuntimeException');

        $collection = new InstalledPackagesTransfer();
        $finder = new InstalledPackageFinder($collection, $this->getProcessMockWithErrorResponse(), '');

        $finder->findInstalledPackages();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Process
     */
    private function getProcessMockWithSuccessFullResponse()
    {
        $processMock = $this->getMock('Symfony\Component\Process\Process', ['isSuccessful', 'run', 'getOutput'], [], '', false);
        $processMock->expects($this->once())
            ->method('isSuccessful')
            ->will($this->returnValue(true));

        $processMock->expects($this->once())
            ->method('run');

        $processMock->expects($this->once())
            ->method('getOutput')
            ->will($this->returnValue($this->getValidResponse()));

        return $processMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Process
     */
    private function getProcessMockWithErrorResponse()
    {
        $processMock = $this->getMock('Symfony\Component\Process\Process', ['isSuccessful', 'run', 'getErrorOutput'], [], '', false);
        $processMock->expects($this->once())
            ->method('isSuccessful')
            ->will($this->returnValue(false));

        $processMock->expects($this->once())
            ->method('run');

        $processMock->expects($this->once())
            ->method('getErrorOutput')
            ->will($this->returnValue('error'));

        return $processMock;
    }

    /**
     * @return string
     */
    private function getValidResponse()
    {
        return '
            {
                "dependencies": {
                    "root-dependency": {
                        "name": "root-dependency",
                        "version": "1",
                        "homepage": "root-dependency",
                        "licenses": "Mit",
                        "dependencies": {
                            "sub-dependency": {
                                "name": "sub-dependency",
                                "version": "1",
                                "homepage": "sub-dependency-homepage",
                                "licenses": [
                                   {
                                       "type": "MIT"
                                   }
                                ]
                            }
                        }
                    }
                }
            }
        ';
    }

}
