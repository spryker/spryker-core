<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SetupFrontend\Business\Model\Builder\Builder;
use Spryker\Zed\SetupFrontend\Business\Model\Cleaner\Cleaner;
use Spryker\Zed\SetupFrontend\Business\Model\Installer\DependencyInstaller;
use Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\InstallMultiPathFinder;
use Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\InstallPathFinder;
use Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathFinderInterface;
use Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathPatternValidator;
use Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathPatternValidatorInterface;
use Spryker\Zed\SetupFrontend\Business\Model\Installer\ProjectInstaller;
use Spryker\Zed\SetupFrontend\Business\Model\PackageManager\NodeInstaller;

/**
 * @method \Spryker\Zed\SetupFrontend\SetupFrontendConfig getConfig()
 */
class SetupFrontendBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\PackageManager\PackageManagerInstallerInterface
     */
    public function createPackageManagerInstaller()
    {
        return new NodeInstaller();
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\DependencyInstallerInterface
     */
    public function createProjectInstaller()
    {
        return new ProjectInstaller($this->getConfig()->getProjectInstallCommand());
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Cleaner\CleanerInterface
     */
    public function createProjectDependencyCleaner()
    {
        return new Cleaner($this->getConfig()->getProjectFrontendDependencyDirectories());
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Cleaner\CleanerInterface
     */
    public function createYvesAssetsCleaner()
    {
        return new Cleaner($this->getConfig()->getYvesAssetsDirectories());
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\DependencyInstallerInterface
     */
    public function createYvesDependencyInstaller()
    {
        return new DependencyInstaller(
            $this->createInstallMultiPathFinderForYves(),
            $this->getConfig()->getYvesInstallCommand()
        );
    }

    /**
     * @deprecated Use createInstallMultiPathFinderForYves() instead
     *
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathFinderInterface
     */
    protected function createYvesInstallerPathFinder()
    {
        return new InstallPathFinder($this->getConfig()->getYvesInstallerDirectoryPattern());
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathFinderInterface
     */
    public function createInstallMultiPathFinderForYves(): PathFinderInterface
    {
        return new InstallMultiPathFinder(
            $this->getConfig()->getYvesInstallMultiPathDirectoryPatterns(),
            $this->createPathPatternValidator()
        );
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Builder\BuilderInterface
     */
    public function createYvesBuilder()
    {
        return new Builder($this->getConfig()->getYvesBuildCommand());
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Cleaner\CleanerInterface
     */
    public function createZedAssetsCleaner()
    {
        return new Cleaner($this->getConfig()->getZedAssetsDirectories());
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\DependencyInstallerInterface
     */
    public function createZedDependencyInstaller()
    {
        return new DependencyInstaller(
            $this->createInstallMultiPathFinderForZed(),
            $this->getConfig()->getZedInstallCommand()
        );
    }

    /**
     * @deprecated Use createInstallMultiPathFinderForZed() instead
     *
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathFinderInterface
     */
    protected function createZedInstallerPathFinder()
    {
        return new InstallPathFinder($this->getConfig()->getZedInstallerDirectoryPattern());
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathFinderInterface
     */
    public function createInstallMultiPathFinderForZed(): PathFinderInterface
    {
        return new InstallMultiPathFinder(
            $this->getConfig()->getZedInstallMultiPathDirectoryPatterns(),
            $this->createPathPatternValidator()
        );
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Builder\BuilderInterface
     */
    public function createZedBuilder()
    {
        return new Builder($this->getConfig()->getZedBuildCommand());
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathPatternValidatorInterface
     */
    public function createPathPatternValidator(): PathPatternValidatorInterface
    {
        return new PathPatternValidator();
    }
}
