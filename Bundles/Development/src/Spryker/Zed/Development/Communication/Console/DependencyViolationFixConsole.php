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
use Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Business\DevelopmentBusinessFactory getFactory()
 */
class DependencyViolationFixConsole extends Console
{
    protected const COMMAND_NAME = 'dev:dependency:fix';
    protected const ARGUMENT_MODULE = 'module';
    protected const OPTION_DRY_RUN = 'dry-run';
    protected const OPTION_DRY_RUN_SHORT = 'd';

    protected const REPLACE_4_WITH_2_SPACES = '/^(  +?)\\1(?=[^ ])/m';

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
            ->setName(static::COMMAND_NAME)
            ->addArgument(static::ARGUMENT_MODULE, InputArgument::OPTIONAL, 'Module to run composer dependency fix for. You must use dot syntax for namespaced ones, e.g. `SprykerEco.FooBar`.')
            ->addOption(static::OPTION_DRY_RUN, 'd', InputOption::VALUE_NONE, 'Dry-run the command, changed composer.json will not be saved.')
            ->setDescription('Fix dependency violations in composer.json.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $modulesToValidate = $this->getModulesToForViolationFix($input);

        if ($this->isSingleModuleFix($modulesToValidate) && !$this->isModuleNameValid($modulesToValidate)) {
            $namespacedModuleName = $this->buildCollectionKey($modulesToValidate);
            $output->writeln(sprintf('Requested module <fg=green>%s</> not found in current scope.', $namespacedModuleName));

            return static::CODE_ERROR;
        }

        foreach ($modulesToValidate as $moduleTransfer) {
            if ($moduleTransfer->getIsStandalone()) {
                $output->writeln(sprintf('<fg=yellow>%s</> is a standalone module and will be skipped.', $moduleTransfer->getName()));
                $output->writeln('');
                continue;
            }
            $this->fixModuleDependencies($moduleTransfer, $output);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function fixModuleDependencies(ModuleTransfer $moduleTransfer, OutputInterface $output): void
    {
        $moduleDependencyTransferCollection = $this->getModuleDependencies($moduleTransfer);

        $moduleViolationCount = $this->getDependencyViolationCount($moduleDependencyTransferCollection);
        if ($moduleViolationCount > 0) {
            $composerJsonFile = $moduleTransfer->getRootDirectory() . '/composer.json';
            $composerJsonContent = file_get_contents($composerJsonFile);
            $composerJsonArray = json_decode($composerJsonContent, true);

            $output->writeln('');
            $output->writeln(sprintf('Fixing dependencies in <fg=yellow>%s.%s</>', $moduleTransfer->getOrganization()->getName(), $moduleTransfer->getName()));

            foreach ($moduleDependencyTransferCollection as $moduleDependencyTransfer) {
                if ($moduleDependencyTransfer->getIsValid()) {
                    continue;
                }

                $module = $moduleDependencyTransfer->getModule();
                $dependencyModuleTransfer = $this->getModuleTransfer($module);

                $moduleNameToFix = sprintf('%s/%s', $dependencyModuleTransfer->getOrganization()->getNameDashed(), $dependencyModuleTransfer->getNameDashed());

                $validationMessagesTransferCollection = $moduleDependencyTransfer->getValidationMessages();
                foreach ($validationMessagesTransferCollection as $validationMessageTransfer) {
                    if (ValidationRuleInterface::ADD_REQUIRE === $validationMessageTransfer->getFixType()) {
                        $composerJsonArray['require'][$moduleNameToFix] = '*';
                        if ($output->isVerbose()) {
                            $output->writeln(sprintf('<fg=green>%s</> added to require', $moduleNameToFix));
                        }
                    }
                    if (ValidationRuleInterface::REMOVE_REQUIRE === $validationMessageTransfer->getFixType()) {
                        unset($composerJsonArray['require'][$moduleNameToFix]);
                        if ($output->isVerbose()) {
                            $output->writeln(sprintf('<fg=green>%s</> removed from require', $moduleNameToFix));
                        }
                    }

                    if (ValidationRuleInterface::ADD_REQUIRE_DEV === $validationMessageTransfer->getFixType()) {
                        $composerJsonArray['require-dev'][$moduleNameToFix] = '*';
                        if ($output->isVerbose()) {
                            $output->writeln(sprintf('<fg=green>%s</> added to require-dev', $moduleNameToFix));
                        }
                    }
                    if (ValidationRuleInterface::REMOVE_REQUIRE_DEV === $validationMessageTransfer->getFixType()) {
                        unset($composerJsonArray['require-dev'][$moduleNameToFix]);
                        if ($output->isVerbose()) {
                            $output->writeln(sprintf('<fg=green>%s</> removed from require-dev', $moduleNameToFix));
                        }
                    }

                    if (ValidationRuleInterface::ADD_SUGGEST === $validationMessageTransfer->getFixType()) {
                        $composerJsonArray['suggest'][$moduleNameToFix] = 'ADD SUGGEST DESCRIPTION';
                        if ($output->isVerbose()) {
                            $output->writeln(sprintf('<fg=green>%s</> added to suggests', $moduleNameToFix));
                        }
                    }
                }
            }

            $output->writeln(sprintf('Fixed dependencies in <fg=yellow>%s.%s</>', $moduleTransfer->getOrganization()->getName(), $moduleTransfer->getName()));

            if ($this->input->getOption(static::OPTION_DRY_RUN)) {
                return;
            }

            $composerJsonArray = $this->orderEntriesInComposerJsonArray($composerJsonArray);

            $modifiedComposerJson = json_encode($composerJsonArray, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            $modifiedComposerJson = preg_replace(static::REPLACE_4_WITH_2_SPACES, '$1', $modifiedComposerJson) . PHP_EOL;

            file_put_contents($composerJsonFile, $modifiedComposerJson);
        }
    }

    /**
     * @param array $composerJsonArray
     *
     * @return array
     */
    protected function orderEntriesInComposerJsonArray(array $composerJsonArray): array
    {
        $entriesToSort = ['require', 'require-dev', 'suggest'];
        foreach ($entriesToSort as $keyToSort) {
            if (isset($composerJsonArray[$keyToSort])) {
                $arrayToSort = $composerJsonArray[$keyToSort];

                ksort($arrayToSort);

                $composerJsonArray[$keyToSort] = $arrayToSort;
            }
        }

        return $composerJsonArray;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleDependencyTransfer[]|\ArrayObject
     */
    protected function getModuleDependencies(ModuleTransfer $moduleTransfer): ArrayObject
    {
        $dependencyValidationRequestTransfer = new DependencyValidationRequestTransfer();
        $dependencyValidationRequestTransfer->setModule($moduleTransfer);

        $dependencyValidationResponseTransfer = $this->getFacade()->validateModuleDependencies($dependencyValidationRequestTransfer);

        return $dependencyValidationResponseTransfer->getModuleDependencies();
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleDependencyTransfer[]|\ArrayObject $moduleDependencyTransferCollection
     *
     * @return int
     */
    protected function getDependencyViolationCount(ArrayObject $moduleDependencyTransferCollection): int
    {
        $violationCountInModule = 0;
        foreach ($moduleDependencyTransferCollection as $moduleDependencyTransfer) {
            $violationCountInModule = $violationCountInModule + count($moduleDependencyTransfer->getValidationMessages());
        }

        return $violationCountInModule;
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

        return $this->getFromModuleTransferCollectionByModuleName($module);
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
     * @param string $module
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function getFromModuleTransferCollectionByModuleName(string $module): ModuleTransfer
    {
        $moduleTransferCollection = $this->getModuleTransferCollection()[$module];
        if (count($moduleTransferCollection) > 1) {
            throw new Exception(sprintf('Module name "%s" is not unique across namespaces', $module));
        }

        return current($moduleTransferCollection);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return array
     */
    protected function getModulesToForViolationFix(InputInterface $input): array
    {
        $moduleTransferCollection = $this->getModuleTransferCollection();
        $module = $input->getArgument(static::ARGUMENT_MODULE);

        if ($module) {
            $moduleTransfer = $this->getModuleTransfer($module);
            $moduleTransferCollection = [$module => $moduleTransfer];
        }

        return $moduleTransferCollection;
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
    protected function isSingleModuleFix(array $modulesToValidate): bool
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
        $currentModule = $this->getCurrentModule($modulesToValidate);
        $collectionKey = $this->buildCollectionKey($currentModule);

        if (!isset($moduleTransferCollection[$collectionKey])) {
            return false;
        }

        return true;
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
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return string
     */
    protected function buildCollectionKey(ModuleTransfer $moduleTransfer): string
    {
        return sprintf('%s.%s', $moduleTransfer->getOrganization()->getName(), $moduleTransfer->getName());
    }
}
