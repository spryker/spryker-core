<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\InstalledPackages\Composer;

use Generated\Shared\Maintenance\InstalledPackagesInterface;
use Generated\Shared\Transfer\InstalledPackageTransfer;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\InstalledPackageFinderInterface;

class InstalledPackageFinder implements InstalledPackageFinderInterface
{

    /**
     * @var InstalledPackagesInterface
     */
    private $collection;

    /**
     * @var string
     */
    private $pathToComposerLock;

    /**
     * @param InstalledPackagesInterface $collection
     * @param string $pathToComposerLock
     */
    public function __construct(InstalledPackagesInterface $collection, $pathToComposerLock)
    {
        $this->collection = $collection;
        $this->pathToComposerLock = $pathToComposerLock;
    }

    /**
     * @return InstalledPackagesInterface
     */
    public function findInstalledPackages()
    {
        $lockFileContent = file_get_contents($this->pathToComposerLock);
        $lockFileData = json_decode($lockFileContent, true);

        foreach ($lockFileData['packages'] as $package) {
            $installedPackage = new InstalledPackageTransfer();
            $installedPackage->setName($package['name']);
            $installedPackage->setVersion($package['version']);

            if (!array_key_exists('license', $package)) {
                $package['license'] = 'n/a';
            }
            $installedPackage->setLicense((array) $package['license']);

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
