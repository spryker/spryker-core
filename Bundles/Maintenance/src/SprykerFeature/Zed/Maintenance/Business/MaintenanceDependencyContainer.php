<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business;

use Generated\Shared\Maintenance\InstalledPackagesInterface;
use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\MaintenanceBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\BundleCodeStyleFixer;
use SprykerFeature\Zed\Maintenance\Business\Dependency\BundleParser;
use SprykerFeature\Zed\Maintenance\Business\Dependency\Graph;
use SprykerFeature\Zed\Maintenance\Business\Dependency\Manager;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\Composer\InstalledPackageFinder;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorInterface;
use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\MarkDownWriter;
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

        $collector = $this->getFactory()->createInstalledPackagesInstalledPackageCollector(
            $collection,
            $finder
        );

        $collector = $this->getFactory()->createInstalledPackagesInstalledPackageCollectorFilter($collector);

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

    /**
     * @return Graph
     */
    public function createDependencyGraph()
    {
        $bundleParser = $this->createDependencyBundleParser();
        $manager = $this->createDependencyManager();

        return $this->getFactory()->createDependencyGraph($bundleParser, $manager);
    }

    /**
     * @return BundleParser
     */
    public function createDependencyBundleParser()
    {
        $config = $this->getConfig();

        return $this->getFactory()->createDependencyBundleParser($config);
    }

    /**
     * @return Manager
     */
    public function createDependencyManager()
    {
        $bundleParser = $this->createDependencyBundleParser();

        return $this->getFactory()->createDependencyManager($bundleParser);
    }

    /**
     * @return BundleCodeStyleFixer
     */
    public function createBundleCodeStyleFixer()
    {
        return $this->getFactory()->createCodeStyleFixerBundleCodeStyleFixer(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getBundleDirectory()
        );
    }

    /**
     * @return PropelMigrationCleanerInterface
     */
    public function createPropelMigrationCleaner()
    {
        return $this->getFactory()->createModelPropelMigrationCleaner(
            $this->createPropelBaseFolderFinder()
        );
    }

    /**
     * @return PropelBaseFolderFinder
     */
    public function createPropelBaseFolderFinder()
    {
        return $this->getFactory()->createModelPropelBaseFolderFinder($this->getConfig()->getPathToSpryker());
    }

}
