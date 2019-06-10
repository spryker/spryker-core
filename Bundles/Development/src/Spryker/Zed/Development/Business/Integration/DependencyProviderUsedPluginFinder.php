<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Integration;

use Generated\Shared\Transfer\ApplicationTransfer;
use Generated\Shared\Transfer\DependencyProviderCollectionTransfer;
use Generated\Shared\Transfer\DependencyProviderTransfer;
use Generated\Shared\Transfer\ModuleFilterTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Generated\Shared\Transfer\PluginTransfer;
use Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeInterface;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class DependencyProviderUsedPluginFinder implements DependencyProviderUsedPluginFinderInterface
{
    /**
     * @var \Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeInterface
     */
    protected $moduleFinderFacade;

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeInterface $moduleFinderFacade
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(DevelopmentToModuleFinderFacadeInterface $moduleFinderFacade, DevelopmentConfig $config)
    {
        $this->moduleFinderFacade = $moduleFinderFacade;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer|null $moduleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\DependencyProviderCollectionTransfer
     */
    public function getUsedPlugins(?ModuleFilterTransfer $moduleFilterTransfer = null): DependencyProviderCollectionTransfer
    {
        $projectModules = $this->moduleFinderFacade->getProjectModules($moduleFilterTransfer);
        $dependencyProviderCollectionTransfer = new DependencyProviderCollectionTransfer();

        foreach ($projectModules as $moduleTransfer) {
            $dependencyProviderCollectionTransfer = $this->addPluginUsageInModuleApplications(
                $moduleTransfer,
                $dependencyProviderCollectionTransfer
            );
        }

        return $dependencyProviderCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     * @param \Generated\Shared\Transfer\DependencyProviderCollectionTransfer $dependencyProviderCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DependencyProviderCollectionTransfer
     */
    protected function addPluginUsageInModuleApplications(ModuleTransfer $moduleTransfer, DependencyProviderCollectionTransfer $dependencyProviderCollectionTransfer): DependencyProviderCollectionTransfer
    {
        foreach ($moduleTransfer->getApplications() as $applicationTransfer) {
            $dependencyProviderCollectionTransfer = $this->addPluginUsageInModule($moduleTransfer, $applicationTransfer, $dependencyProviderCollectionTransfer);
        }

        return $dependencyProviderCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     * @param \Generated\Shared\Transfer\ApplicationTransfer $applicationTransfer
     * @param \Generated\Shared\Transfer\DependencyProviderCollectionTransfer $dependencyProviderCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DependencyProviderCollectionTransfer
     */
    protected function addPluginUsageInModule(ModuleTransfer $moduleTransfer, ApplicationTransfer $applicationTransfer, DependencyProviderCollectionTransfer $dependencyProviderCollectionTransfer)
    {
        $finder = $this->getFinderForModule(
            $this->getPath($moduleTransfer, $applicationTransfer)
        );

        foreach ($finder as $splFileInfo) {
            $dependencyProviderTransfer = $this->buildDependencyProviderTransfer($splFileInfo, $moduleTransfer);
            $dependencyProviderCollectionTransfer = $this->addPluginUsages($dependencyProviderCollectionTransfer, $dependencyProviderTransfer, $splFileInfo);
        }

        return $dependencyProviderCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     * @param \Generated\Shared\Transfer\ApplicationTransfer $applicationTransfer
     *
     * @return string
     */
    protected function getPath(ModuleTransfer $moduleTransfer, ApplicationTransfer $applicationTransfer): string
    {
        return sprintf(
            '%ssrc/%s/%s/%s/',
            $moduleTransfer->getPath(),
            $moduleTransfer->getOrganization()->getName(),
            $applicationTransfer->getName(),
            $moduleTransfer->getName()
        );
    }

    /**
     * @param string $path
     *
     * @return \Symfony\Component\Finder\SplFileInfo[]|\Symfony\Component\Finder\Finder
     */
    protected function getFinderForModule(string $path): Finder
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in($path)
            ->name('/DependencyProvider.php/')
            ->sort($this->getFilenameSortCallback());

        return $finder;
    }

    /**
     * @return callable
     */
    protected function getFilenameSortCallback(): callable
    {
        return function (SplFileInfo $splFileInfoOne, SplFileInfo $splFileInfoTwo) {
            return strcmp($splFileInfoOne->getRealPath(), $splFileInfoTwo->getRealPath());
        };
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Generated\Shared\Transfer\DependencyProviderTransfer
     */
    protected function buildDependencyProviderTransfer(SplFileInfo $splFileInfo, ModuleTransfer $moduleTransfer): DependencyProviderTransfer
    {
        $dependencyProviderClassName = str_replace([$moduleTransfer->getPath() . 'src/', '.php', DIRECTORY_SEPARATOR], ['', '', '\\'], $splFileInfo->getPathname());
        $classNameFragments = explode('\\', $dependencyProviderClassName);

        $dependencyProviderTransfer = new DependencyProviderTransfer();
        $dependencyProviderTransfer
            ->setFullyQualifiedClassName($dependencyProviderClassName)
            ->setClassName(array_pop($classNameFragments))
            ->setModule($moduleTransfer);

        return $dependencyProviderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyProviderCollectionTransfer $dependencyProviderCollectionTransfer
     * @param \Generated\Shared\Transfer\DependencyProviderTransfer $dependencyProviderTransfer
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return \Generated\Shared\Transfer\DependencyProviderCollectionTransfer
     */
    protected function addPluginUsages(DependencyProviderCollectionTransfer $dependencyProviderCollectionTransfer, DependencyProviderTransfer $dependencyProviderTransfer, SplFileInfo $splFileInfo): DependencyProviderCollectionTransfer
    {
        preg_match_all('/use (.*?);/', $splFileInfo->getContents(), $matches, PREG_SET_ORDER);
        if (count($matches) === 0) {
            return $dependencyProviderCollectionTransfer;
        }

        $dependencyProviderTransfer = $this->addUsedPlugins($dependencyProviderTransfer, $matches);
        $dependencyProviderCollectionTransfer->addDependencyProvider($dependencyProviderTransfer);

        return $dependencyProviderCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyProviderTransfer $dependencyProviderTransfer
     * @param array $useStatements
     *
     * @return \Generated\Shared\Transfer\DependencyProviderTransfer
     */
    protected function addUsedPlugins(DependencyProviderTransfer $dependencyProviderTransfer, array $useStatements): DependencyProviderTransfer
    {
        foreach ($useStatements as $match) {
            if (preg_match('/Plugin/', $match[1])) {
                $pluginTransfer = $this->buildPluginTransfer($match[1]);
                $dependencyProviderTransfer->addUsedPlugin($pluginTransfer);
            }
        }

        return $dependencyProviderTransfer;
    }

    /**
     * @param string $pluginClassName
     *
     * @return \Generated\Shared\Transfer\PluginTransfer
     */
    protected function buildPluginTransfer(string $pluginClassName): PluginTransfer
    {
        $moduleTransfer = $this->buildModuleTransferFromClassName($pluginClassName);
        $classNameFragments = explode('\\', $pluginClassName);

        $pluginTransfer = new PluginTransfer();
        $pluginTransfer
            ->setFullyQualifiedClassName($pluginClassName)
            ->setClassName(array_pop($classNameFragments))
            ->setModule($moduleTransfer);

        return $pluginTransfer;
    }

    /**
     * @param string $className
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function buildModuleTransferFromClassName(string $className): ModuleTransfer
    {
        $classNameFragments = explode('\\', $className);

        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer
            ->setName($classNameFragments[0])
            ->setIsProject($this->isProjectOrganization($classNameFragments[0]));

        $applicationTransfer = new ApplicationTransfer();
        $applicationTransfer
            ->setName($classNameFragments[1]);

        $moduleTransfer = new ModuleTransfer();
        $moduleTransfer
            ->setName($classNameFragments[2])
            ->setOrganization($organizationTransfer)
            ->setApplication($applicationTransfer)
            ->setIsStandalone(false);

        return $moduleTransfer;
    }

    /**
     * @param string $organization
     *
     * @return bool
     */
    protected function isProjectOrganization(string $organization): bool
    {
        return in_array($organization, $this->config->getProjectNamespaces(), true);
    }
}
