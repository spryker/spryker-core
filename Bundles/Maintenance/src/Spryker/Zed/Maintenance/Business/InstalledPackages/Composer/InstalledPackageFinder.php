<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business\InstalledPackages\Composer;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Generated\Shared\Transfer\InstalledPackageTransfer;
use Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageFinderInterface;

class InstalledPackageFinder implements InstalledPackageFinderInterface
{

    /**
     * @var \Generated\Shared\Transfer\InstalledPackagesTransfer
     */
    private $collection;

    /**
     * @var string
     */
    private $pathToComposerLock;

    /**
     * @param \Generated\Shared\Transfer\InstalledPackagesTransfer $collection
     * @param string $pathToComposerLock
     */
    public function __construct(InstalledPackagesTransfer $collection, $pathToComposerLock)
    {
        $this->collection = $collection;
        $this->pathToComposerLock = $pathToComposerLock;
    }

    /**
     * @return \Generated\Shared\Transfer\InstalledPackagesTransfer
     */
    public function findInstalledPackages()
    {
        $lockFileContent = file_get_contents($this->pathToComposerLock);
        $lockFileData = json_decode($lockFileContent, true);
        $packages = array_merge($lockFileData['packages'], $lockFileData['packages-dev']);

        foreach ($packages as $package) {
            $installedPackage = new InstalledPackageTransfer();
            $installedPackage->setName($package['name']);
            $installedPackage->setVersion($package['version']);

            if (!array_key_exists('license', $package)) {
                $package['license'] = 'n/a';
            }
            $installedPackage->setLicense((array)$package['license']);

            if (!array_key_exists('homepage', $package)) {
                $package['homepage'] = 'n/a';
            }
            $installedPackage->setUrl($package['homepage']);
            $installedPackage->setType('Composer');

            $this->collection->addPackage($installedPackage);
        }

        return $this->collection;
    }

}
