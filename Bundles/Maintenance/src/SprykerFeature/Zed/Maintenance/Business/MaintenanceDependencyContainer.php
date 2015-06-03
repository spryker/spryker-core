<?php

namespace SprykerFeature\Zed\Maintenance\Business;

use Generated\Shared\Maintenance\InstalledPackagesInterface;
use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\MaintenanceBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\Composer\InstalledPackageFinder;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectionInterface;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorInterface;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\MarkDownWriter;
use SprykerFeature\Zed\Maintenance\MaintenanceConfig;
use Symfony\Component\Process\Process;

/**
 * @method MaintenanceBusiness getFactory()
 * @method MaintenanceConfig getConfig()
 */
class MaintenanceDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return InstalledPackageCollectorInterface
     */
    public function createPackageCollector()
    {
        $collection = new InstalledPackagesTransfer();
        $finder = [];
        $finder[] = $this->createComposerInstalledPackageFinder($collection);
        $finder[] = $this->createNodePackageManagerInstalledPackageFinder(
            $collection,
            $this->getConfig()->getPathToRoot()
        );
        $finder[] = $this->createNodePackageManagerInstalledPackageFinder(
            $collection,
            $this->getConfig()->getPathToSpryker()
        );

        $collector = $this->getFactory()->createInstalledPackagesInstalledPackageCollector(
            $collection,
            $finder
        );

        return $collector;
    }

    /**
     * @param InstalledPackagesInterface $collection
     *
     * @return InstalledPackageFinder
     */
    private function createComposerInstalledPackageFinder(InstalledPackagesInterface $collection)
    {
        return $this->getFactory()->createInstalledPackagesComposerInstalledPackageFinder(
            $collection,
            $this->getConfig()->getPathToComposerLock()
        );
    }

    /**
     * @param InstalledPackagesInterface $collection
     *
     * @return InstalledPackageFinder
     */
    private function createNodePackageManagerInstalledPackageFinder(InstalledPackagesInterface $collection, $path)
    {
        return $this->getFactory()->createInstalledPackagesNodePackageManagerInstalledPackageFinder(
            $collection,
            $this->createNpmListProcess(),
            $path
        );
    }

    /**
     * @return Process
     */
    private function createNpmListProcess()
    {
        return new Process('npm list -json -long');
    }

    /**
     * @param InstalledPackagesInterface $installedPackages
     *
     * @return MarkDownWriter
     */
    public function createMarkDownWriter(InstalledPackagesInterface $installedPackages)
    {
        return $this->getFactory()->createInstalledPackagesMarkDownWriter(
            $installedPackages,
            $this->getConfig()->getPathToFossFile()
        );
    }

}
