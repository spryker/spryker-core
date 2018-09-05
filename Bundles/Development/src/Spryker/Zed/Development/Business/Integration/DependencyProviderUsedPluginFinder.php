<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Integration;

use Generated\Shared\Transfer\ApplicationTransfer;
use Generated\Shared\Transfer\DependencyProviderCollectionTransfer;
use Generated\Shared\Transfer\DependencyProviderTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Generated\Shared\Transfer\PluginTransfer;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class DependencyProviderUsedPluginFinder implements DependencyProviderUsedPluginFinderInterface
{
    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(DevelopmentConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Generated\Shared\Transfer\DependencyProviderCollectionTransfer
     */
    public function findUsedPlugins(): DependencyProviderCollectionTransfer
    {
        $dependencyProviderCollectionTransfer = new DependencyProviderCollectionTransfer();

        $finder = $this->getFinder();
        foreach ($finder as $splFileObject) {
            $dependencyProviderCollectionTransfer = $this->addPluginUsages($dependencyProviderCollectionTransfer, $splFileObject);
        }

        return $dependencyProviderCollectionTransfer;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function getFinder(): Finder
    {
        $finder = new Finder();
        $finder->files()->in(APPLICATION_SOURCE_DIR)->name('/DependencyProvider.php/');

        return $finder;
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyProviderCollectionTransfer $dependencyProviderCollectionTransfer
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return \Generated\Shared\Transfer\DependencyProviderCollectionTransfer
     */
    protected function addPluginUsages(DependencyProviderCollectionTransfer $dependencyProviderCollectionTransfer, SplFileInfo $splFileInfo): DependencyProviderCollectionTransfer
    {
        preg_match_all('/use (.*?);/', $splFileInfo->getContents(), $matches, PREG_SET_ORDER);
        if (count($matches) === 0) {
            return $dependencyProviderCollectionTransfer;
        }

        $dependencyProviderTransfer = $this->buildDependencyProviderTransfer($splFileInfo);
        $dependencyProviderTransfer = $this->addUsedPlugins($dependencyProviderTransfer, $matches);

        $dependencyProviderCollectionTransfer->addDependencyProvider($dependencyProviderTransfer);

        return $dependencyProviderCollectionTransfer;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return \Generated\Shared\Transfer\DependencyProviderTransfer
     */
    protected function buildDependencyProviderTransfer(SplFileInfo $splFileInfo): DependencyProviderTransfer
    {
        $dependencyProviderClassName = str_replace(['.php', DIRECTORY_SEPARATOR], ['', '\\'], $splFileInfo->getRelativePathname());

        $moduleTransfer = $this->buildModuleTransferFromClassName($dependencyProviderClassName);

        $dependencyProviderTransfer = new DependencyProviderTransfer();
        $dependencyProviderTransfer
            ->setClassName($dependencyProviderClassName)
            ->setModule($moduleTransfer);

        return $dependencyProviderTransfer;
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

        $pluginTransfer = new PluginTransfer();
        $pluginTransfer
            ->setClassName($pluginClassName)
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
