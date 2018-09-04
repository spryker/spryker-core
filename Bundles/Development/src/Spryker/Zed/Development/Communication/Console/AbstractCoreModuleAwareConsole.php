<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\DependencyValidationRequestTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Business\DevelopmentBusinessFactory getFactory()
 */
class AbstractCoreModuleAwareConsole extends Console
{
    protected const ARGUMENT_MODULE = 'module';
    protected const OPTION_EXPLICIT_MODULE = 'explicit-module';
    protected const OPTION_EXPLICIT_MODULE_SHORT = 'e';

    /**
     * @var array
     */
    protected $moduleTransferCollection = [];

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->addArgument(static::ARGUMENT_MODULE, InputArgument::OPTIONAL, 'Module to run checks for. You must use dot syntax for namespaced ones, e.g. `SprykerEco.FooBar` or `Spryker.all` or `Spryker.M`. The latter syntax will find all modules starting with M, this can be more explicit by using more letters matching the modules you want to run checks for.')
            ->addOption(static::OPTION_EXPLICIT_MODULE, static::OPTION_EXPLICIT_MODULE_SHORT, InputOption::VALUE_NONE, 'When module syntax like `Spryker.Module` is used and more than one module matches criteria you can make it more explicit by using this option.');
    }

    /**
     * @param array $modulesToValidate
     *
     * @return bool
     */
    protected function canRun(array $modulesToValidate): bool
    {
        if (count($modulesToValidate) === 0) {
            $this->output->writeln('Could not find any module to fix. Maybe you have a typo in the module argument or you missed to use a proper organization prefix.');

            return false;
        }

        if ($this->isSingleModuleValidation($modulesToValidate) && !$this->isModuleNameValid($modulesToValidate)) {
            $namespacedModuleName = $this->buildModuleKey(current($modulesToValidate));
            $this->output->writeln(sprintf('Requested module <fg=green>%s</> not found in current scope.', $namespacedModuleName));

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     * @param string|null $dependencyType
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ModuleDependencyTransfer[]
     */
    protected function getModuleDependencies(ModuleTransfer $moduleTransfer, ?string $dependencyType = null): ArrayObject
    {
        $dependencyValidationRequestTransfer = new DependencyValidationRequestTransfer();
        $dependencyValidationRequestTransfer->setModule($moduleTransfer);
        $dependencyValidationRequestTransfer->setDependencyType($dependencyType);

        $dependencyValidationResponseTransfer = $this->getFacade()->validateModuleDependencies($dependencyValidationRequestTransfer);

        return $dependencyValidationResponseTransfer->getModuleDependencies();
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleDependencyTransfer[]|\ArrayObject $moduleDependencyTransferCollection
     * @param string|null $dependencyType
     *
     * @return int
     */
    protected function getDependencyViolationCount(ArrayObject $moduleDependencyTransferCollection, ?string $dependencyType = null): int
    {
        $violationCountInModule = 0;
        foreach ($moduleDependencyTransferCollection as $moduleDependencyTransfer) {
            if ($dependencyType !== null && !in_array($dependencyType, $moduleDependencyTransfer->getDependencyTypes())) {
                continue;
            }

            $violationCountInModule = $violationCountInModule + count($moduleDependencyTransfer->getValidationMessages());
        }

        return $violationCountInModule;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    protected function getModulesToCheckForViolations(InputInterface $input): array
    {
        $module = $input->getArgument(static::ARGUMENT_MODULE);

        if ($module) {
            return $this->filterModuleTransferCollectionByKey($this->buildFilterKey($module));
        }

        return $this->getModuleTransferCollection();
    }

    /**
     * @param string $module
     *
     * @return string
     */
    protected function buildFilterKey(string $module): string
    {
        if (!$this->isNamespacedModuleName($module)) {
            return $module;
        }

        [$organizationName, $moduleName] = explode('.', $module);

        if ($moduleName === 'all') {
            return $organizationName . '.';
        }

        return $module;
    }

    /**
     * @param string $filterKey
     *
     * @return array
     */
    protected function filterModuleTransferCollectionByKey(string $filterKey): array
    {
        $filteredModuleTransferCollection = [];
        $moduleTransferCollection = $this->getModuleTransferCollection();
        foreach ($moduleTransferCollection as $moduleKey => $moduleTransfer) {
            if (!($moduleTransfer instanceof ModuleTransfer)) {
                continue;
            }
            if (!$this->input->getOption(static::OPTION_EXPLICIT_MODULE) && strpos($moduleKey, $filterKey) === 0) {
                $filteredModuleTransferCollection[$moduleKey] = $moduleTransfer;
            }
            if ($this->input->getOption(static::OPTION_EXPLICIT_MODULE) && $moduleKey === $filterKey) {
                $filteredModuleTransferCollection[$moduleKey] = $moduleTransfer;
            }
        }

        return $filteredModuleTransferCollection;
    }

    /**
     * @param string $module
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function getModuleTransfer(string $module): ModuleTransfer
    {
        if ($this->isNamespacedModuleName($module)) {
            return $this->getModuleTransferCollection()[$module];
        }

        return $this->findInModuleTransferCollectionByModuleName($module);
    }

    /**
     * @param array $modulesToValidate
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function getCurrentModule(array $modulesToValidate): ModuleTransfer
    {
        return current($modulesToValidate);
    }

    /**
     * @param string $module
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function findInModuleTransferCollectionByModuleName(string $module): ModuleTransfer
    {
        $moduleTransferCollection = $this->getModuleTransferCollection();

        if (!isset($moduleTransferCollection[$module])) {
            throw new Exception(sprintf('Module name "%s" not not found in module transfer collection.', $module));
        }

        $moduleTransferCollection = $moduleTransferCollection[$module];

        if (count($moduleTransferCollection) > 1) {
            throw new Exception(sprintf('Module name "%s" is not unique across namespaces.', $module));
        }

        return current($moduleTransferCollection);
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
     * @return array
     */
    protected function getModuleTransferCollection(): array
    {
        if (!$this->moduleTransferCollection) {
            $this->moduleTransferCollection = $this->getFacade()->getModules();
        }

        return $this->moduleTransferCollection;
    }

    /**
     * @param array $modulesToValidate
     *
     * @return bool
     */
    protected function isSingleModuleValidation(array $modulesToValidate): bool
    {
        if (count($modulesToValidate) > 1) {
            return false;
        }

        return true;
    }

    /**
     * @param array $modulesToValidate
     *
     * @return bool
     */
    protected function isModuleNameValid(array $modulesToValidate): bool
    {
        $moduleTransferCollection = $this->getModuleTransferCollection();
        $currentModuleTransfer = current($modulesToValidate);

        if (!isset($moduleTransferCollection[$this->buildModuleKey($currentModuleTransfer)])) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return string
     */
    protected function buildModuleKey(ModuleTransfer $moduleTransfer): string
    {
        return sprintf('%s.%s', $moduleTransfer->getOrganization()->getName(), $moduleTransfer->getName());
    }
}
