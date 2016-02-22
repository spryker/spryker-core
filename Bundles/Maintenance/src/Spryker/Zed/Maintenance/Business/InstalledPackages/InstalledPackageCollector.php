<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business\InstalledPackages;

use Generated\Shared\Transfer\InstalledPackagesTransfer;

class InstalledPackageCollector implements InstalledPackageCollectorInterface
{

    /**
     * @var \Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageFinderInterface[]
     */
    private $installedPackageFinder;

    /**
     * @var \Generated\Shared\Transfer\InstalledPackagesTransfer
     */
    private $installedPackageCollection;

    /**
     * @param \Generated\Shared\Transfer\InstalledPackagesTransfer $installedPackageCollection
     * @param array $installedPackageFinder
     */
    public function __construct(InstalledPackagesTransfer $installedPackageCollection, array $installedPackageFinder)
    {
        $this->installedPackageCollection = $installedPackageCollection;
        $this->installedPackageFinder = $installedPackageFinder;
    }

    /**
     * @return \Generated\Shared\Transfer\InstalledPackagesTransfer
     */
    public function getInstalledPackages()
    {
        $this->executeFinder();

        return $this->installedPackageCollection;
    }

    /**
     * @return void
     */
    private function executeFinder()
    {
        foreach ($this->installedPackageFinder as $finder) {
            $finder->findInstalledPackages();
        }
    }

}
