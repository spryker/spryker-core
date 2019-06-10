<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SetupFrontend\Business\Model\Builder\Builder;
use Spryker\Zed\SetupFrontend\Business\Model\Builder\BuilderInterface;
use Spryker\Zed\SetupFrontend\Business\Model\Cleaner\Cleaner;
use Spryker\Zed\SetupFrontend\Business\Model\Cleaner\CleanerInterface;
use Spryker\Zed\SetupFrontend\Business\Model\Cleaner\YvesAssetsCleaner;
use Spryker\Zed\SetupFrontend\Business\Model\Generator\YvesAssetsBuildConfigGenerator;
use Spryker\Zed\SetupFrontend\Business\Model\Generator\YvesAssetsBuildConfigGeneratorInterface;
use Spryker\Zed\SetupFrontend\Business\Model\Installer\DependencyInstaller;
use Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\InstallPathFinder;
use Spryker\Zed\SetupFrontend\Business\Model\Installer\ProjectInstaller;
use Spryker\Zed\SetupFrontend\Business\Model\PackageManager\NodeInstaller;
use Spryker\Zed\SetupFrontend\Business\Model\Resolver\BuilderCommandResolver;
use Spryker\Zed\SetupFrontend\Business\Model\Resolver\BuilderCommandResolverInterface;
use Spryker\Zed\SetupFrontend\Dependency\Service\SetupFrontendToUtilEncodingServiceInterface;
use Spryker\Zed\SetupFrontend\SetupFrontendDependencyProvider;

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
    public function createYvesAssetsCleaner(): CleanerInterface
    {
        return new YvesAssetsCleaner(
            $this->getConfig(),
            $this->getStore()
        );
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\DependencyInstallerInterface
     */
    public function createYvesDependencyInstaller()
    {
        return new DependencyInstaller(
            $this->createYvesInstallerPathFinder(),
            $this->getConfig()->getYvesInstallCommand()
        );
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathFinderInterface
     */
    protected function createYvesInstallerPathFinder()
    {
        return new InstallPathFinder($this->getConfig()->getYvesInstallerDirectoryPattern());
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Builder\BuilderInterface
     */
    public function createYvesBuilder(): BuilderInterface
    {
        return new Builder($this->createBuilderCommandResolver()->getYvesBuildCommand());
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Resolver\BuilderCommandResolverInterface
     */
    public function createBuilderCommandResolver(): BuilderCommandResolverInterface
    {
        return new BuilderCommandResolver(
            $this->getConfig(),
            $this->getStore()
        );
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
            $this->createZedInstallerPathFinder(),
            $this->getConfig()->getZedInstallCommand()
        );
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathFinderInterface
     */
    protected function createZedInstallerPathFinder()
    {
        return new InstallPathFinder($this->getConfig()->getZedInstallerDirectoryPattern());
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Builder\BuilderInterface
     */
    public function createZedBuilder()
    {
        return new Builder($this->getConfig()->getZedBuildCommand());
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Business\Model\Generator\YvesAssetsBuildConfigGeneratorInterface
     */
    public function createYvesAssetsBuildConfigGenerator(): YvesAssetsBuildConfigGeneratorInterface
    {
        return new YvesAssetsBuildConfigGenerator(
            $this->getConfig(),
            $this->getUtilEncodingService(),
            $this->getStore(),
            $this->getYvesFrontendStoreConfigExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(SetupFrontendDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Zed\SetupFrontend\Dependency\Service\SetupFrontendToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): SetupFrontendToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(SetupFrontendDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\SetupFrontendExtension\Dependency\YvesFrontendStoreConfigExpanderPluginInterface[]
     */
    public function getYvesFrontendStoreConfigExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SetupFrontendDependencyProvider::PLUGINS_YVES_FRONTEND_STORE_CONFIG_EXPANDER);
    }
}
