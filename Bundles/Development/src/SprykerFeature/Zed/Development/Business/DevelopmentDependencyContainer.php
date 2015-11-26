<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Development\Business;

use Generated\Shared\Development\InstalledPackagesInterface;
use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\DevelopmentBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Development\Business\CodeStyleFixer\BundleCodeStyleFixer;
use SprykerFeature\Zed\Development\Business\Dependency\BundleParser;
use SprykerFeature\Zed\Development\Business\Dependency\Graph;
use SprykerFeature\Zed\Development\Business\Dependency\Manager;
use SprykerFeature\Zed\Development\Business\InstalledPackages\Composer\InstalledPackageFinder;
use SprykerFeature\Zed\Development\Business\InstalledPackages\InstalledPackageCollectorInterface;
use SprykerFeature\Zed\Development\Business\InstalledPackages\MarkDownWriter;
use SprykerFeature\Zed\Development\Business\Model\PropelBaseFolderFinder;
use SprykerFeature\Zed\Development\Business\Model\PropelMigrationCleanerInterface;
use SprykerFeature\Zed\Development\DevelopmentConfig;
use Symfony\Component\Process\Process;

/**
 * @method DevelopmentBusiness getFactory()
 * @method DevelopmentConfig getConfig()
 */
class DevelopmentDependencyContainer extends AbstractBusinessDependencyContainer
{

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

}
