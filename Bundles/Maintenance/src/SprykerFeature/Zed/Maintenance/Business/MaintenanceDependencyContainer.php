<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\MaintenanceBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Maintenance\Business\Dependency\BundleParser;
use SprykerFeature\Zed\Maintenance\Business\Dependency\Graph;
use SprykerFeature\Zed\Maintenance\Business\Dependency\Manager;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\Composer\InstalledPackageFinder as ComposerInstalledPackageFinder;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorInterface;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\MarkDownWriter;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\NodePackageManager\InstalledPackageFinder as NodeInstalledPackageFinder;
use SprykerFeature\Zed\Maintenance\Business\Model\PropelBaseFolderFinder;
use SprykerFeature\Zed\Maintenance\Business\Model\PropelMigrationCleanerInterface;
use SprykerFeature\Zed\Maintenance\MaintenanceConfig;
use Symfony\Component\Process\Process;

/**
 * @method MaintenanceBusiness getFactory()
 * @method MaintenanceConfig getConfig()
 */
class MaintenanceDependencyContainer extends AbstractBusinessDependencyContainer
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

        $collector = new \SprykerFeature\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollector(
            $collection,
            $finder
        );

        $collector = new \SprykerFeature\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorFilter($collector);

        return $collector;
    }

    /**
     * @param InstalledPackagesTransfer $collection
     *
     * @return ComposerInstalledPackageFinder
     */
    private function createComposerInstalledPackageFinder(InstalledPackagesTransfer $collection)
    {
        return new \SprykerFeature\Zed\Maintenance\Business\InstalledPackages\Composer\InstalledPackageFinder(
            $collection,
            $this->getConfig()->getPathToComposerLock()
        );
    }

    /**
     * @param InstalledPackagesTransfer $collection
     *
     * @return NodeInstalledPackageFinder
     */
    private function createNodePackageManagerInstalledPackageFinder(InstalledPackagesTransfer $collection, $path)
    {
        return new \SprykerFeature\Zed\Maintenance\Business\InstalledPackages\NodePackageManager\InstalledPackageFinder(
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
     * @param InstalledPackagesTransfer $installedPackages
     *
     * @return MarkDownWriter
     */
    public function createMarkDownWriter(InstalledPackagesTransfer $installedPackages)
    {
        return new \SprykerFeature\Zed\Maintenance\Business\InstalledPackages\MarkDownWriter(
            $installedPackages,
            $this->getConfig()->getPathToFossFile()
        );
    }

    /**
     * @return Graph
     */
    public function createDependencyGraph()
    {
        $bundleParser = $this->createDependencyBundleParser();
        $manager = $this->createDependencyManager();

        return new \SprykerFeature\Zed\Maintenance\Business\Dependency\Graph($bundleParser, $manager);
    }

    /**
     * @return BundleParser
     */
    public function createDependencyBundleParser()
    {
        $config = $this->getConfig();

        return new \SprykerFeature\Zed\Maintenance\Business\Dependency\BundleParser($config);
    }

    /**
     * @return Manager
     */
    public function createDependencyManager()
    {
        $bundleParser = $this->createDependencyBundleParser();

        return new \SprykerFeature\Zed\Maintenance\Business\Dependency\Manager($bundleParser);
    }

    /**
     * @return PropelMigrationCleanerInterface
     */
    public function createPropelMigrationCleaner()
    {
        return new \SprykerFeature\Zed\Maintenance\Business\Model\PropelMigrationCleaner(
            $this->createPropelBaseFolderFinder()
        );
    }

    /**
     * @return PropelBaseFolderFinder
     */
    public function createPropelBaseFolderFinder()
    {
        return new \SprykerFeature\Zed\Maintenance\Business\Model\PropelBaseFolderFinder($this->getConfig()->getPathToSpryker());
    }

}
