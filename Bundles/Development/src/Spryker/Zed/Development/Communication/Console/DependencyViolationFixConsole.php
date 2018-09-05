<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Exception;
use Generated\Shared\Transfer\ModuleDependencyTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\ValidationMessageTransfer;
use Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Business\DevelopmentBusinessFactory getFactory()
 */
class DependencyViolationFixConsole extends AbstractCoreModuleAwareConsole
{
    protected const COMMAND_NAME = 'dev:dependency:fix';
    protected const OPTION_DRY_RUN = 'dry-run';
    protected const OPTION_DRY_RUN_SHORT = 'd';

    protected const REPLACE_4_WITH_2_SPACES = '/^(  +?)\\1(?=[^ ])/m';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
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
        $modulesToValidate = $this->getModulesToCheckForViolations($input);

        if (!$this->canRun($modulesToValidate)) {
            return static::CODE_ERROR;
        }

        foreach ($modulesToValidate as $index => $moduleTransfer) {
            if (!$this->isNamespacedModuleName($index) || $moduleTransfer->getIsStandalone()) {
                continue;
            }
            $this->executeModuleTransfer($moduleTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return void
     */
    protected function executeModuleTransfer(ModuleTransfer $moduleTransfer): void
    {
        $moduleDependencyTransferCollection = $this->getModuleDependencies($moduleTransfer);
        $moduleViolationCount = $this->getDependencyViolationCount($moduleDependencyTransferCollection);

        if ($moduleViolationCount === 0) {
            $this->output->writeln(sprintf('No dependency issues found in <fg=yellow>%s.%s</>', $moduleTransfer->getOrganization()->getName(), $moduleTransfer->getName()));

            return;
        }

        $this->fixModuleDependencies($moduleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return void
     */
    protected function fixModuleDependencies(ModuleTransfer $moduleTransfer): void
    {
        $composerJsonArray = $this->getComposerJsonAsArray($moduleTransfer);

        foreach ($this->getModuleDependencies($moduleTransfer) as $moduleDependencyTransfer) {
            if (!$this->canFixDependencies($moduleTransfer, $moduleDependencyTransfer)) {
                continue;
            }

            $dependencyModuleTransfer = $this->getModuleTransfer($moduleDependencyTransfer->getModule());

            $moduleNameToFix = sprintf('%s/%s', $dependencyModuleTransfer->getOrganization()->getNameDashed(), $dependencyModuleTransfer->getNameDashed());
            $composerJsonArray = $this->fixDependencyViolations($moduleDependencyTransfer, $composerJsonArray, $moduleNameToFix);
        }

        $this->output->writeln(sprintf('Fixed dependencies in <fg=yellow>%s.%s</>', $moduleTransfer->getOrganization()->getName(), $moduleTransfer->getName()));

        $this->saveComposerJsonArray($moduleTransfer, $composerJsonArray);
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     * @param \Generated\Shared\Transfer\ModuleDependencyTransfer $moduleDependencyTransfer
     *
     * @return bool
     */
    protected function canFixDependencies(ModuleTransfer $moduleTransfer, ModuleDependencyTransfer $moduleDependencyTransfer): bool
    {
        if ($moduleDependencyTransfer->getIsValid()) {
            return false;
        }

        $module = $moduleDependencyTransfer->getModule();
        try {
            $this->getModuleTransfer($module);
        } catch (Exception $exception) {
            $this->output->writeln(sprintf('<bg=red>%s</>', $exception->getMessage()));
            $this->output->writeln(sprintf('Please check the module <fg=yellow>%s.%s</> manually.', $moduleTransfer->getOrganization()->getName(), $moduleTransfer->getName()));
            $this->output->writeln('');

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    protected function getComposerJsonAsArray(ModuleTransfer $moduleTransfer): array
    {
        $composerJsonFile = $moduleTransfer->getPath() . '/composer.json';
        $composerJsonContent = file_get_contents($composerJsonFile);
        $composerJsonArray = json_decode($composerJsonContent, true);

        return $composerJsonArray;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     * @param array $composerJsonArray
     *
     * @return void
     */
    protected function saveComposerJsonArray(ModuleTransfer $moduleTransfer, array $composerJsonArray): void
    {
        if ($this->input->getOption(static::OPTION_DRY_RUN)) {
            return;
        }

        $composerJsonFile = $moduleTransfer->getPath() . '/composer.json';
        $composerJsonArray = $this->orderEntriesInComposerJsonArray($composerJsonArray);
        $composerJsonArray = $this->removeEmptyEntriesInComposerJsonArray($composerJsonArray);

        $modifiedComposerJson = json_encode($composerJsonArray, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        $modifiedComposerJson = preg_replace(static::REPLACE_4_WITH_2_SPACES, '$1', $modifiedComposerJson) . PHP_EOL;

        file_put_contents($composerJsonFile, $modifiedComposerJson);
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleDependencyTransfer $moduleDependencyTransfer
     * @param array $composerJsonArray
     * @param string $moduleNameToFix
     *
     * @return array
     */
    protected function fixDependencyViolations(ModuleDependencyTransfer $moduleDependencyTransfer, array $composerJsonArray, string $moduleNameToFix): array
    {
        foreach ($moduleDependencyTransfer->getValidationMessages() as $validationMessageTransfer) {
            $composerJsonArray = $this->fixDependencyViolationsInRequire($validationMessageTransfer, $composerJsonArray, $moduleNameToFix);
            $composerJsonArray = $this->fixDependencyViolationsInRequireDev($validationMessageTransfer, $composerJsonArray, $moduleNameToFix);
            $composerJsonArray = $this->fixDependencyViolationsInSuggest($validationMessageTransfer, $composerJsonArray, $moduleNameToFix);
        }

        return $composerJsonArray;
    }

    /**
     * @param \Generated\Shared\Transfer\ValidationMessageTransfer $validationMessageTransfer
     * @param array $composerJsonArray
     * @param string $moduleNameToFix
     *
     * @return array
     */
    protected function fixDependencyViolationsInRequire(ValidationMessageTransfer $validationMessageTransfer, array $composerJsonArray, string $moduleNameToFix): array
    {
        if ($validationMessageTransfer->getFixType() === ValidationRuleInterface::ADD_REQUIRE) {
            $composerJsonArray['require'][$moduleNameToFix] = '*';
            $this->writeIfVerbose(sprintf('<fg=green>%s</> added to require', $moduleNameToFix));
        }
        if ($validationMessageTransfer->getFixType() === ValidationRuleInterface::REMOVE_REQUIRE) {
            unset($composerJsonArray['require'][$moduleNameToFix]);
            $this->writeIfVerbose(sprintf('<fg=green>%s</> removed from require', $moduleNameToFix));
        }

        return $composerJsonArray;
    }

    /**
     * @param \Generated\Shared\Transfer\ValidationMessageTransfer $validationMessageTransfer
     * @param array $composerJsonArray
     * @param string $moduleNameToFix
     *
     * @return array
     */
    protected function fixDependencyViolationsInRequireDev(ValidationMessageTransfer $validationMessageTransfer, array $composerJsonArray, string $moduleNameToFix): array
    {
        if ($validationMessageTransfer->getFixType() === ValidationRuleInterface::ADD_REQUIRE_DEV) {
            $composerJsonArray['require-dev'][$moduleNameToFix] = '*';
            $this->writeIfVerbose(sprintf('<fg=green>%s</> added to require-dev', $moduleNameToFix));
        }
        if ($validationMessageTransfer->getFixType() === ValidationRuleInterface::REMOVE_REQUIRE_DEV) {
            unset($composerJsonArray['require-dev'][$moduleNameToFix]);
            $this->writeIfVerbose(sprintf('<fg=green>%s</> removed from require-dev', $moduleNameToFix));
        }

        return $composerJsonArray;
    }

    /**
     * @param \Generated\Shared\Transfer\ValidationMessageTransfer $validationMessageTransfer
     * @param array $composerJsonArray
     * @param string $moduleNameToFix
     *
     * @return array
     */
    protected function fixDependencyViolationsInSuggest(ValidationMessageTransfer $validationMessageTransfer, array $composerJsonArray, string $moduleNameToFix): array
    {
        if ($validationMessageTransfer->getFixType() === ValidationRuleInterface::ADD_SUGGEST) {
            $composerJsonArray['suggest'][$moduleNameToFix] = 'ADD SUGGEST DESCRIPTION';
            $this->writeIfVerbose(sprintf('<fg=green>%s</> added to suggests', $moduleNameToFix));
        }
        if ($validationMessageTransfer->getFixType() === ValidationRuleInterface::REMOVE_SUGGEST) {
            unset($composerJsonArray['suggest'][$moduleNameToFix]);
            $this->writeIfVerbose(sprintf('<fg=green>%s</> removed from suggests', $moduleNameToFix));
        }

        return $composerJsonArray;
    }

    /**
     * @param string $message
     *
     * @return void
     */
    protected function writeIfVerbose(string $message): void
    {
        if ($this->output->isVerbose()) {
            $this->output->writeln($message);
        }
    }

    /**
     * @param array $composerJsonArray
     *
     * @return array
     */
    protected function orderEntriesInComposerJsonArray(array $composerJsonArray): array
    {
        $keys = ['require', 'require-dev', 'suggest'];
        foreach ($keys as $key) {
            if (isset($composerJsonArray[$key])) {
                $arrayToSort = $composerJsonArray[$key];

                ksort($arrayToSort);

                $composerJsonArray[$key] = $arrayToSort;
            }
        }

        return $composerJsonArray;
    }

    /**
     * @param array $composerJsonArray
     *
     * @return array
     */
    protected function removeEmptyEntriesInComposerJsonArray(array $composerJsonArray): array
    {
        $keys = ['require', 'require-dev', 'suggest'];
        foreach ($keys as $key) {
            if (isset($composerJsonArray[$key]) && count($composerJsonArray[$key]) === 0) {
                unset($composerJsonArray[$key]);
            }
        }

        return $composerJsonArray;
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
}
