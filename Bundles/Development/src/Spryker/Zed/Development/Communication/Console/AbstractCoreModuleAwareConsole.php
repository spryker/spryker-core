<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use ArrayObject;
use Generated\Shared\Transfer\ApplicationTransfer;
use Generated\Shared\Transfer\DependencyValidationRequestTransfer;
use Generated\Shared\Transfer\ModuleFilterTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Business\DevelopmentBusinessFactory getFactory()
 */
class AbstractCoreModuleAwareConsole extends Console
{
    protected const ARGUMENT_MODULE = 'module';

    /**
     * @var \Generated\Shared\Transfer\ModuleTransfer[]
     */
    protected $moduleTransferCollection = [];

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->addArgument(static::ARGUMENT_MODULE, InputArgument::OPTIONAL, 'Module(s) to execute. Organization.Module or Organization.Application.Module can be used. You can also make a wildcard search by prefix or suffix parts with asterisk (*)')
            ->setHelp('
For whitespace search you can prefix or suffix all module relevant parts with the asterisk (*)

You can use the following search patterns:

- Organization.Module
- Organization*.Module
- *Organization.Module
- Organization.Module
- Organization.Module*
- Organization.*Module
- Organization.Application.Module
- Organization.Application*.Module
- Organization.*Application.Module

Asterisk can also be used more than once in all parts. Currently, it\'s not possible to use it in the middle of one of the parts e.g. Spryker.Foo*Bar is invalid. 
            ');
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
    protected function getModulesToExecute(InputInterface $input): array
    {
        $moduleFilterTransfer = $this->buildModuleFilterTransfer();
        if ($moduleFilterTransfer === null) {
            return $this->getModuleTransferCollection();
        }

        return $this->getFacade()->getModules($moduleFilterTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ModuleFilterTransfer|null
     */
    protected function buildModuleFilterTransfer(): ?ModuleFilterTransfer
    {
        if (!$this->input->getArgument(static::ARGUMENT_MODULE)) {
            return null;
        }

        $moduleFilterTransfer = new ModuleFilterTransfer();
        $moduleArgument = $this->input->getArgument(static::ARGUMENT_MODULE);

        if (strpos($moduleArgument, '.') === false) {
            $moduleTransfer = new ModuleTransfer();
            $moduleTransfer->setName($moduleArgument);
            $moduleFilterTransfer->setModule($moduleTransfer);

            return $moduleFilterTransfer;
        }

        $this->addFilterDetails($moduleArgument, $moduleFilterTransfer);

        return $moduleFilterTransfer;
    }

    /**
     * @param string $moduleArgument
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer $moduleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleFilterTransfer
     */
    protected function addFilterDetails(string $moduleArgument, ModuleFilterTransfer $moduleFilterTransfer): ModuleFilterTransfer
    {
        $moduleFragments = explode('.', $moduleArgument);

        $organization = array_shift($moduleFragments);
        $application = array_shift($moduleFragments);
        $module = array_shift($moduleFragments);

        if ($module === null) {
            $module = $application;
            $application = null;
        }

        $moduleFilterTransfer = $this->addModuleTransfer($moduleFilterTransfer, $module);
        $moduleFilterTransfer = $this->addOrganizationTransfer($moduleFilterTransfer, $organization);
        $moduleFilterTransfer = $this->addApplicationTransfer($moduleFilterTransfer, $application);

        return $moduleFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer $moduleFilterTransfer
     * @param string $module
     *
     * @return \Generated\Shared\Transfer\ModuleFilterTransfer
     */
    protected function addModuleTransfer(ModuleFilterTransfer $moduleFilterTransfer, string $module): ModuleFilterTransfer
    {
        if ($module !== '*' && $module !== 'all') {
            $moduleTransfer = new ModuleTransfer();
            $moduleTransfer->setName($module);
            $moduleFilterTransfer->setModule($moduleTransfer);
        }

        return $moduleFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer $moduleFilterTransfer
     * @param string $organization
     *
     * @return \Generated\Shared\Transfer\ModuleFilterTransfer
     */
    protected function addOrganizationTransfer(ModuleFilterTransfer $moduleFilterTransfer, string $organization): ModuleFilterTransfer
    {
        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer->setName($organization);

        $moduleFilterTransfer->setOrganization($organizationTransfer);

        return $moduleFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer $moduleFilterTransfer
     * @param string|null $application
     *
     * @return \Generated\Shared\Transfer\ModuleFilterTransfer
     */
    protected function addApplicationTransfer(ModuleFilterTransfer $moduleFilterTransfer, ?string $application = null): ModuleFilterTransfer
    {
        if ($application === null) {
            return $moduleFilterTransfer;
        }
        $applicationTransfer = new ApplicationTransfer();
        $applicationTransfer->setName($application);

        $moduleFilterTransfer->setApplication($applicationTransfer);

        return $moduleFilterTransfer;
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
     * @param string $module
     *
     * @return bool
     */
    protected function isNamespacedModuleName(string $module): bool
    {
        return (strpos($module, '.') !== false);
    }

    /**
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
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
