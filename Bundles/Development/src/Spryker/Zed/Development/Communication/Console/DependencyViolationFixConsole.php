<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Generated\Shared\Transfer\ModuleDependencyTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\ValidationMessageTransfer;
use Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 *
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
        $modulesToValidate = $this->getModulesToExecute($input);

        if (!$this->canRun($modulesToValidate)) {
            return static::CODE_ERROR;
        }

        foreach ($modulesToValidate as $index => $moduleTransfer) {
            if (!$this->isNamespacedModuleName($index)) {
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
            $composerNameToFix = $this->getFacade()->findComposerNameByModuleName($moduleDependencyTransfer->getModuleName());

            if ($composerNameToFix === null) {
                $this->output->writeln(sprintf('Could not get a composer name for "%s"', $moduleDependencyTransfer->getModuleName()));
                $this->output->writeln(sprintf('Please check the module <fg=yellow>%s.%s</> manually.', $moduleTransfer->getOrganization()->getName(), $moduleTransfer->getName()));
                continue;
            }

            $composerJsonArray = $this->fixDependencyViolations($moduleDependencyTransfer, $composerJsonArray, $composerNameToFix);
        }

        $this->output->writeln(sprintf('Fixed dependencies in <fg=yellow>%s.%s</>', $moduleTransfer->getOrganization()->getName(), $moduleTransfer->getName()));

        $this->saveComposerJsonArray($moduleTransfer, $composerJsonArray);
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
     * @param string $composerName
     *
     * @return array
     */
    protected function fixDependencyViolations(ModuleDependencyTransfer $moduleDependencyTransfer, array $composerJsonArray, string $composerName): array
    {
        foreach ($moduleDependencyTransfer->getValidationMessages() as $validationMessageTransfer) {
            $composerJsonArray = $this->fixDependencyViolationsInRequire($validationMessageTransfer, $composerJsonArray, $composerName);
            $composerJsonArray = $this->fixDependencyViolationsInRequireDev($validationMessageTransfer, $composerJsonArray, $composerName);
            $composerJsonArray = $this->fixDependencyViolationsInSuggest($validationMessageTransfer, $composerJsonArray, $composerName);
        }

        return $composerJsonArray;
    }

    /**
     * @param \Generated\Shared\Transfer\ValidationMessageTransfer $validationMessageTransfer
     * @param array $composerJsonArray
     * @param string $composerName
     *
     * @return array
     */
    protected function fixDependencyViolationsInRequire(ValidationMessageTransfer $validationMessageTransfer, array $composerJsonArray, string $composerName): array
    {
        if ($validationMessageTransfer->getFixType() === ValidationRuleInterface::ADD_REQUIRE) {
            $composerJsonArray['require'][$composerName] = '*';
            $this->writeIfVerbose(sprintf('<fg=green>%s</> added to require', $composerName));
        }
        if ($validationMessageTransfer->getFixType() === ValidationRuleInterface::REMOVE_REQUIRE) {
            unset($composerJsonArray['require'][$composerName]);
            $this->writeIfVerbose(sprintf('<fg=green>%s</> removed from require', $composerName));
        }

        return $composerJsonArray;
    }

    /**
     * @param \Generated\Shared\Transfer\ValidationMessageTransfer $validationMessageTransfer
     * @param array $composerJsonArray
     * @param string $composerName
     *
     * @return array
     */
    protected function fixDependencyViolationsInRequireDev(ValidationMessageTransfer $validationMessageTransfer, array $composerJsonArray, string $composerName): array
    {
        if ($validationMessageTransfer->getFixType() === ValidationRuleInterface::ADD_REQUIRE_DEV) {
            $composerJsonArray['require-dev'][$composerName] = '*';
            $this->writeIfVerbose(sprintf('<fg=green>%s</> added to require-dev', $composerName));
        }
        if ($validationMessageTransfer->getFixType() === ValidationRuleInterface::REMOVE_REQUIRE_DEV) {
            unset($composerJsonArray['require-dev'][$composerName]);
            $this->writeIfVerbose(sprintf('<fg=green>%s</> removed from require-dev', $composerName));
        }

        return $composerJsonArray;
    }

    /**
     * @param \Generated\Shared\Transfer\ValidationMessageTransfer $validationMessageTransfer
     * @param array $composerJsonArray
     * @param string $composerName
     *
     * @return array
     */
    protected function fixDependencyViolationsInSuggest(ValidationMessageTransfer $validationMessageTransfer, array $composerJsonArray, string $composerName): array
    {
        if ($validationMessageTransfer->getFixType() === ValidationRuleInterface::ADD_SUGGEST) {
            $composerJsonArray['suggest'][$composerName] = 'ADD SUGGEST DESCRIPTION';
            $this->writeIfVerbose(sprintf('<fg=green>%s</> added to suggests', $composerName));
        }
        if ($validationMessageTransfer->getFixType() === ValidationRuleInterface::REMOVE_SUGGEST) {
            unset($composerJsonArray['suggest'][$composerName]);
            $this->writeIfVerbose(sprintf('<fg=green>%s</> removed from suggests', $composerName));
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
}
