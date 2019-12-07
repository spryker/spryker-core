<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyContainer;

use ArrayObject;
use Generated\Shared\Transfer\DependencyCollectionTransfer;
use Generated\Shared\Transfer\DependencyModuleTransfer;
use Generated\Shared\Transfer\DependencyTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Zend\Filter\Word\DashToCamelCase;

class DependencyContainer implements DependencyContainerInterface
{
    /**
     * @var \Generated\Shared\Transfer\DependencyCollectionTransfer
     */
    protected $dependencyCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return $this
     */
    public function initialize(ModuleTransfer $moduleTransfer)
    {
        $this->dependencyCollectionTransfer = new DependencyCollectionTransfer();
        $this->dependencyCollectionTransfer->setModule($moduleTransfer);

        return $this;
    }

    /**
     * @param string $moduleOrComposerName
     * @param string $type
     * @param bool $isOptional
     * @param bool $isTest
     *
     * @return $this
     */
    public function addDependency(string $moduleOrComposerName, string $type, bool $isOptional = false, bool $isTest = false)
    {
        $moduleName = $moduleOrComposerName;
        $composerName = null;

        if (strpos($moduleOrComposerName, '/') !== false) {
            $composerName = $moduleOrComposerName;
            $moduleName = $this->getModuleNameFromComposerName($composerName);
        }

        $dependencyTransfer = new DependencyTransfer();
        $dependencyTransfer
            ->setModule($moduleName)
            ->setComposerName($composerName)
            ->setType($type)
            ->setIsOptional($isOptional)
            ->setIsInTest($isTest);

        $dependencyModuleTransfer = $this->getDependencyModuleTransfer($dependencyTransfer);
        $dependencyModuleTransfer->addDependency($dependencyTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyTransfer $dependencyTransfer
     *
     * @return \Generated\Shared\Transfer\DependencyModuleTransfer
     */
    protected function getDependencyModuleTransfer(DependencyTransfer $dependencyTransfer): DependencyModuleTransfer
    {
        foreach ($this->dependencyCollectionTransfer->getDependencyModules() as $dependencyModuleTransfer) {
            if ($dependencyModuleTransfer->getModule() === $dependencyTransfer->getModule()) {
                return $dependencyModuleTransfer;
            }
        }

        $dependencyModuleTransfer = new DependencyModuleTransfer();
        $dependencyModuleTransfer->setModule($dependencyTransfer->getModule());
        $dependencyModuleTransfer->setComposerName($dependencyTransfer->getComposerName());

        $this->dependencyCollectionTransfer->addDependencyModule($dependencyModuleTransfer);

        return $dependencyModuleTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\DependencyCollectionTransfer
     */
    public function getDependencyCollection(): DependencyCollectionTransfer
    {
        return $this->sortDependencies($this->dependencyCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $dependencyCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DependencyCollectionTransfer
     */
    protected function sortDependencies(DependencyCollectionTransfer $dependencyCollectionTransfer): DependencyCollectionTransfer
    {
        $callback = function (DependencyModuleTransfer $dependencyBundleTransferA, DependencyModuleTransfer $dependencyBundleTransferB) {
            return strcmp($dependencyBundleTransferA->getModule(), $dependencyBundleTransferB->getModule());
        };

        $dependencyModules = $dependencyCollectionTransfer->getDependencyModules()->getArrayCopy();

        usort($dependencyModules, $callback);

        $dependencyCollectionTransfer->setDependencyModules(new ArrayObject());

        foreach ($dependencyModules as $dependencyModule) {
            $dependencyCollectionTransfer->addDependencyModule($dependencyModule);
        }

        return $dependencyCollectionTransfer;
    }

    /**
     * @param string $composerName
     *
     * @return string
     */
    protected function getModuleNameFromComposerName(string $composerName): string
    {
        [$organizationName, $moduleName] = explode('/', $composerName);

        $filter = new DashToCamelCase();

        return ucfirst($filter->filter($moduleName));
    }
}
