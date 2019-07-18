<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module;

use Generated\Shared\Transfer\ModuleOverviewTransfer;
use Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeInterface;

class ModuleOverview implements ModuleOverviewInterface
{
    /**
     * @var \Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeInterface
     */
    protected $moduleFinderFacade;

    /**
     * @param \Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeInterface $moduleFinderFacade
     */
    public function __construct(DevelopmentToModuleFinderFacadeInterface $moduleFinderFacade)
    {
        $this->moduleFinderFacade = $moduleFinderFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\ModuleOverviewTransfer[]
     */
    public function getOverview(): array
    {
        $moduleOverviewTransferCollection = [];
        $moduleOverviewTransferCollection = $this->addProjectModules($moduleOverviewTransferCollection);
        $moduleOverviewTransferCollection = $this->addCoreModules($moduleOverviewTransferCollection);

        ksort($moduleOverviewTransferCollection);

        return $moduleOverviewTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleOverviewTransfer[] $moduleOverviewTransferCollection
     *
     * @return \Generated\Shared\Transfer\ModuleOverviewTransfer[]
     */
    protected function addProjectModules(array $moduleOverviewTransferCollection): array
    {
        $projectModules = $this->moduleFinderFacade->getProjectModules();

        foreach (array_keys($projectModules) as $moduleKey) {
            $moduleName = $this->getModuleNameFromModuleKey($moduleKey);
            $moduleOverviewTransfer = $this->getModuleOverviewTransfer(
                $moduleOverviewTransferCollection,
                $this->getModuleNameFromModuleKey($moduleKey)
            );

            $moduleOverviewTransfer->setExistsProjectModule(true);

            $moduleOverviewTransferCollection[$moduleName] = $moduleOverviewTransfer;
        }

        return $moduleOverviewTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleOverviewTransfer[] $moduleOverviewTransferCollection
     *
     * @return \Generated\Shared\Transfer\ModuleOverviewTransfer[]
     */
    protected function addCoreModules(array $moduleOverviewTransferCollection): array
    {
        $coreModules = $this->moduleFinderFacade->getModules();

        foreach (array_keys($coreModules) as $moduleKey) {
            $moduleName = $this->getModuleNameFromModuleKey($moduleKey);
            $moduleOverviewTransfer = $this->getModuleOverviewTransfer(
                $moduleOverviewTransferCollection,
                $this->getModuleNameFromModuleKey($moduleKey)
            );

            $moduleOverviewTransfer->setExistsCoreModule(true);

            $moduleOverviewTransferCollection[$moduleName] = $moduleOverviewTransfer;
        }

        return $moduleOverviewTransferCollection;
    }

    /**
     * @param string $moduleKey
     *
     * @return string
     */
    protected function getModuleNameFromModuleKey(string $moduleKey): string
    {
        $moduleKeyFragments = explode('.', $moduleKey);

        return array_pop($moduleKeyFragments);
    }

    /**
     * @param array $moduleOverviewTransferCollection
     * @param string $moduleName
     *
     * @return \Generated\Shared\Transfer\ModuleOverviewTransfer
     */
    protected function getModuleOverviewTransfer(array $moduleOverviewTransferCollection, string $moduleName): ModuleOverviewTransfer
    {
        if (isset($moduleOverviewTransferCollection[$moduleName])) {
            return $moduleOverviewTransferCollection[$moduleName];
        }

        return $this->createModuleTransfer($moduleName);
    }

    /**
     * @param string $moduleName
     *
     * @return \Generated\Shared\Transfer\ModuleOverviewTransfer
     */
    protected function createModuleTransfer(string $moduleName): ModuleOverviewTransfer
    {
        $moduleOverviewTransfer = new ModuleOverviewTransfer();
        $moduleOverviewTransfer
            ->setModuleName($moduleName);

        return $moduleOverviewTransfer;
    }
}
