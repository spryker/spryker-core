<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SetupFrontend\Business\Model\Builder\Builder;
use Spryker\Zed\SetupFrontend\Business\Model\Builder\BuilderInterface;
use Spryker\Zed\SetupFrontend\Business\Model\Cleaner\Cleaner;
use Spryker\Zed\SetupFrontend\Business\Model\Installer\DependencyInstaller;
use Spryker\Zed\SetupFrontend\Business\Model\Installer\DependencyInstallerInterface;
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
        return new NodeInstaller($this->getConfig());
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
     * @deprecated In next major single installer will be used. See {@link this->createProjectInstaller()}
     *
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\DependencyInstallerInterface
     */
    public function createYvesDependencyInstaller()
    {
        return new DependencyInstaller(
            $this->createInstallMultiPathFinderForYves(),
            $this->getConfig()->getYvesInstallCommand(),
        );
    }

    /**
     * @deprecated Use {@link createInstallMultiPathFinderForYves()} instead.
     *
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathFinderInterface
     */
    protected function createYvesInstallerPathFinder()
    {
        return new InstallPathFinder($this->getConfig()->getYvesInstallerDirectoryPattern());
    }

    /**
     * @deprecated In next major single installer will be used. See {@link this->createProjectInstaller()}
     *
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathFinderInterface
     */
    public function createInstallMultiPathFinderForYves(): PathFinderInterface
    {
        return new InstallMultiPathFinder(
            $this->getConfig()->getYvesInstallMultiPathDirectoryPatterns(),
            $this->createPathPatternValidator(),
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
     * @deprecated In next major single installer will be used. See {@link this->createProjectInstaller()}
     *
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\DependencyInstallerInterface
     */
    public function createZedDependencyInstaller()
    {
        return new DependencyInstaller(
            $this->createInstallMultiPathFinderForZed(),
            $this->getConfig()->getZedInstallCommand(),
        );
    }

    /**
     * @deprecated Use {@link createInstallMultiPathFinderForZed()} instead.
     *
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathFinderInterface
     */
    protected function createZedInstallerPathFinder()
    {
        return new InstallPathFinder($this->getConfig()->getZedInstallerDirectoryPattern());
    }

    /**
     * @deprecated In next major single installer will be used. See {@link this->createProjectInstaller()}
     *
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathFinderInterface
     */
    public function createInstallMultiPathFinderForZed(): PathFinderInterface
    {
        return new InstallMultiPathFinder(
            $this->getConfig()->getZedInstallMultiPathDirectoryPatterns(),
            $this->createPathPatternValidator(),
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

    /**
     * @deprecated In next major single installer will be used. See {@link this->createProjectInstaller()}
     *
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\DependencyInstallerInterface
     */
    public function createMerchantPortalDependencyInstaller(): DependencyInstallerInterface
    {
        return new ProjectInstaller($this->getConfig()->getMerchantPortalInstallCommand());
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Builder\BuilderInterface
     */
    public function createMerchantPortalBuilder(): BuilderInterface
    {
        return new Builder($this->getConfig()->getMerchantPortalBuildCommand());
    }
}
