<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer;

use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeInterface;

class ComposerNameFinder implements ComposerNameFinderInterface
{
    /**
     * @var \Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeInterface
     */
    protected $moduleFinderFacade;

    /**
     * @var \Generated\Shared\Transfer\ModuleTransfer[]
     */
    protected $moduleTransferCollection = [];

    /**
     * @var \Generated\Shared\Transfer\ModuleTransfer[][]|null
     */
    protected $moduleTransferCollectionGroupedByModuleName;

    /**
     * @var \Generated\Shared\Transfer\PackageTransfer[]|null
     */
    protected $packageTransferCollectionGroupedByPackageName;

    /**
     * @param \Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeInterface $moduleFinderFacade
     */
    public function __construct(DevelopmentToModuleFinderFacadeInterface $moduleFinderFacade)
    {
        $this->moduleFinderFacade = $moduleFinderFacade;
    }

    /**
     * @param string $moduleName
     *
     * @return string|null
     */
    public function findComposerNameByModuleName(string $moduleName): ?string
    {
        $composerName = $this->getComposerNameFromModuleCollection($moduleName);
        if ($composerName !== null) {
            return $composerName;
        }

        $composerName = $this->getComposerNameFromPackageCollection($moduleName);
        if ($composerName !== null) {
            return $composerName;
        }

        return null;
    }

    /**
     * @param string $moduleName
     *
     * @return string|null
     */
    protected function getComposerNameFromModuleCollection(string $moduleName): ?string
    {
        if ($this->isNamespacedModuleName($moduleName)) {
            $moduleTransfer = $this->getModuleTransferCollection()[$moduleName];

            return sprintf('%s/%s', $moduleTransfer->getOrganization()->getNameDashed(), $moduleTransfer->getNameDashed());
        }

        $moduleTransferCollection = $this->getModuleTransferCollectionGroupedByModuleName();

        if (!isset($moduleTransferCollection[$moduleName])) {
            return null;
        }

        if (count($moduleTransferCollection[$moduleName]) > 1) {
            return null;
        }

        $moduleTransfer = $this->getCurrentModuleTransfer($moduleTransferCollection[$moduleName]);

        return sprintf('%s/%s', $moduleTransfer->getOrganization()->getNameDashed(), $moduleTransfer->getNameDashed());
    }

    /**
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    protected function getModuleTransferCollection(): array
    {
        if (!$this->moduleTransferCollection) {
            $this->moduleTransferCollection = $this->moduleFinderFacade->getModules();
        }

        return $this->moduleTransferCollection;
    }

    /**
     * @param string $module
     *
     * @return bool
     */
    protected function isNamespacedModuleName(string $module): bool
    {
        return (strpos($module, '.') !== false);
    }

    /**
     * @param string $moduleName
     *
     * @return string|null
     */
    protected function getComposerNameFromPackageCollection(string $moduleName): ?string
    {
        $packageTransferCollection = $this->getPackageTransferCollectionGroupedByPackageName();

        if (isset($packageTransferCollection[$moduleName])) {
            $packageTransfer = $packageTransferCollection[$moduleName];

            return $packageTransfer->getComposerName();
        }

        return null;
    }

    /**
     * @param array $moduleTransferCollection
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function getCurrentModuleTransfer(array $moduleTransferCollection): ModuleTransfer
    {
        return current($moduleTransferCollection);
    }

    /**
     * @return \Generated\Shared\Transfer\ModuleTransfer[][]
     */
    protected function getModuleTransferCollectionGroupedByModuleName(): array
    {
        if ($this->moduleTransferCollectionGroupedByModuleName !== null) {
            return $this->moduleTransferCollectionGroupedByModuleName;
        }

        $moduleTransferCollection = $this->getModuleTransferCollection();
        $this->moduleTransferCollectionGroupedByModuleName = [];

        foreach ($moduleTransferCollection as $moduleTransfer) {
            $this->moduleTransferCollectionGroupedByModuleName[$moduleTransfer->getName()][] = $moduleTransfer;
        }

        return $this->moduleTransferCollectionGroupedByModuleName;
    }

    /**
     * @return \Generated\Shared\Transfer\PackageTransfer[]
     */
    protected function getPackageTransferCollectionGroupedByPackageName(): array
    {
        if ($this->packageTransferCollectionGroupedByPackageName !== null) {
            return $this->packageTransferCollectionGroupedByPackageName;
        }

        $packageTransferCollection = $this->moduleFinderFacade->getPackages();
        $this->packageTransferCollectionGroupedByPackageName = [];

        foreach ($packageTransferCollection as $packageTransfer) {
            $this->packageTransferCollectionGroupedByPackageName[$packageTransfer->getPackageName()] = $packageTransfer;
        }

        return $this->packageTransferCollectionGroupedByPackageName;
    }
}
