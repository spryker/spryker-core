<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module;

use Generated\Shared\Transfer\ModuleOverviewTransfer;
use Spryker\Zed\Development\Business\Module\ModuleFinder\ModuleFinderInterface;
use Spryker\Zed\Development\Business\Module\ProjectModuleFinder\ProjectModuleFinderInterface;

class ModuleOverview implements ModuleOverviewInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\Module\ProjectModuleFinder\ProjectModuleFinderInterface
     */
    protected $projectModuleFinder;

    /**
     * @var \Spryker\Zed\Development\Business\Module\ModuleFinder\ModuleFinderInterface
     */
    protected $operatingSystemModuleFinder;

    /**
     * @param \Spryker\Zed\Development\Business\Module\ProjectModuleFinder\ProjectModuleFinderInterface $projectModuleFinder
     * @param \Spryker\Zed\Development\Business\Module\ModuleFinder\ModuleFinderInterface $operatingSystemModuleFinder
     */
    public function __construct(ProjectModuleFinderInterface $projectModuleFinder, ModuleFinderInterface $operatingSystemModuleFinder)
    {
        $this->projectModuleFinder = $projectModuleFinder;
        $this->operatingSystemModuleFinder = $operatingSystemModuleFinder;
    }

    /**
     * @return \Generated\Shared\Transfer\ModuleOverviewTransfer[]
     */
    public function getOverview(): array
    {
        $moduleOverview = [];
        $moduleOverview = $this->addProjectModules($moduleOverview);
        $moduleOverview = $this->addOperatingSystemModules($moduleOverview);

        ksort($moduleOverview);

        return $moduleOverview;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleOverviewTransfer[] $moduleOverview
     *
     * @return \Generated\Shared\Transfer\ModuleOverviewTransfer[]
     */
    protected function addProjectModules(array $moduleOverview): array
    {
        $projectModules = $this->projectModuleFinder->getProjectModules();

        foreach (array_keys($projectModules) as $moduleKey) {
            $moduleKeyFragments = explode('.', $moduleKey);
            $moduleName = $moduleKeyFragments[1];
            if (isset($moduleOverview[$moduleName])) {
                $moduleOverviewTransfer = $moduleOverview[$moduleName];
                $moduleOverviewTransfer->setIsProjectModule(true);

                continue;
            }

            $moduleOverviewTransfer = new ModuleOverviewTransfer();
            $moduleOverviewTransfer
                ->setModuleName($moduleName)
                ->setIsProjectModule(true);

            $moduleOverview[$moduleName] = $moduleOverviewTransfer;
        }

        return $moduleOverview;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleOverviewTransfer[] $moduleOverview
     *
     * @return \Generated\Shared\Transfer\ModuleOverviewTransfer[]
     */
    protected function addOperatingSystemModules(array $moduleOverview): array
    {
        $operatingSystemModules = $this->operatingSystemModuleFinder->getModules();

        foreach (array_keys($operatingSystemModules) as $moduleKey) {
            $moduleKeyFragments = explode('.', $moduleKey);
            $moduleName = $moduleKeyFragments[1];
            if (isset($moduleOverview[$moduleName])) {
                $moduleOverviewTransfer = $moduleOverview[$moduleName];
                $moduleOverviewTransfer->setIsOperatingSystemModule(true);

                continue;
            }

            $moduleOverviewTransfer = new ModuleOverviewTransfer();
            $moduleOverviewTransfer
                ->setModuleName($moduleName)
                ->setIsOperatingSystemModule(true);

            $moduleOverview[$moduleName] = $moduleOverviewTransfer;
        }

        return $moduleOverview;
    }
}
