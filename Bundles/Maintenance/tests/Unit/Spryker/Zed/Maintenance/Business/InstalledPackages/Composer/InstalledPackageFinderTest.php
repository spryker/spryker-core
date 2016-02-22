<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Maintenance\Business\InstalledPackages\Composer;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Spryker\Zed\Maintenance\Business\InstalledPackages\Composer\InstalledPackageFinder;

/**
 * @group Spryker
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

    /**
     * @return void
     */
    public function testFindInstalledPackagesShouldReturnCollectionWithInstalledPackages()
    {
        $collection = new InstalledPackagesTransfer();
        $finder = new InstalledPackageFinder($collection, $this->getFixturesDirectory() . DIRECTORY_SEPARATOR . 'composerLock.mock');

        $this->assertInstanceOf(
            'Generated\Shared\Transfer\InstalledPackagesTransfer',
            $finder->findInstalledPackages()
        );
    }

}
