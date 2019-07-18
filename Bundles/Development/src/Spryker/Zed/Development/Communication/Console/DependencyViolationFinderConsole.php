<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use ArrayObject;
use Generated\Shared\Transfer\ModuleDependencyTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 *
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Business\DevelopmentBusinessFactory getFactory()
 */
class DependencyViolationFinderConsole extends AbstractCoreModuleAwareConsole
{
    public const COMMAND_NAME = 'dev:dependency:find';
    public const OPTION_DEPENDENCY_TYPE = 'dependency-type';
    public const OPTION_DEPENDENCY_TYPE_SHORT = 'd';
    public const OPTION_STOP_ON_VIOLATION = 'stop-on-violation';
    public const OPTION_STOP_ON_VIOLATION_SHORT = 's';

    /**
     * @var int
     */
    protected $dependencyViolationCount = 0;

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->addOption(static::OPTION_DEPENDENCY_TYPE, static::OPTION_DEPENDENCY_TYPE_SHORT, InputOption::VALUE_REQUIRED, 'Runs only one specific dependency type check.')
            ->addOption(static::OPTION_STOP_ON_VIOLATION, static::OPTION_STOP_ON_VIOLATION_SHORT, InputOption::VALUE_NONE, 'Stop execution when a violation was found.')
            ->setDescription('Find dependency violations in the modules.');

        $this->setAliases(['dev:dependency:find-violations']);
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

        $dependencyType = $this->getDependencyType($input);

        $this->startValidation($modulesToValidate, $dependencyType);

        foreach ($modulesToValidate as $index => $moduleTransfer) {
            if (!$this->isNamespacedModuleName($index)) {
                continue;
            }
            $this->validateModule($moduleTransfer, $output, $dependencyType);
        }

        return $this->endValidation();
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string|null $dependencyType
     *
     * @return void
     */
    protected function validateModule(ModuleTransfer $moduleTransfer, OutputInterface $output, ?string $dependencyType = null): void
    {
        $this->startModuleValidation($this->buildModuleKey($moduleTransfer));

        $moduleDependencyTransferCollection = $this->getModuleDependencies($moduleTransfer, $dependencyType);

        if ($output->isVeryVerbose()) {
            $this->describeDependencies($moduleDependencyTransferCollection, $output, $dependencyType);
        }

        $moduleViolationCount = $this->getDependencyViolationCount($moduleDependencyTransferCollection, $dependencyType);
        if ($moduleViolationCount > 0) {
            $this->printDependencyViolationErrors($this->buildModuleKey($moduleTransfer), $moduleDependencyTransferCollection, $output, $dependencyType);
            if ($this->input->getOption(static::OPTION_STOP_ON_VIOLATION)) {
                $this->output->writeln(sprintf('Found <fg=red>%s</> errors, stop execution now as requested.', $moduleViolationCount));
                exit(static::CODE_ERROR);
            }
        }

        $this->dependencyViolationCount += $moduleViolationCount;

        $this->endModuleValidation($output, $moduleViolationCount, $dependencyType);
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleDependencyTransfer[]|\ArrayObject $moduleDependencyTransferCollection
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string|null $dependencyType
     *
     * @return void
     */
    protected function describeDependencies(ArrayObject $moduleDependencyTransferCollection, OutputInterface $output, ?string $dependencyType = null): void
    {
        $tableRows = [];

        foreach ($moduleDependencyTransferCollection as $moduleDependencyTransfer) {
            if ($dependencyType !== null && !in_array($dependencyType, $moduleDependencyTransfer->getDependencyTypes())) {
                continue;
            }

            $tableRows[] = $this->buildTableRow($moduleDependencyTransfer);
        }

        if (count($tableRows) > 0) {
            $this->renderTable($output, array_keys(current($tableRows)), $tableRows);
        }
    }

    /**
     * @param string $moduleToValidate
     * @param \Generated\Shared\Transfer\ModuleDependencyTransfer[]|\ArrayObject $moduleDependencyTransferCollection
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string|null $dependencyType
     *
     * @return void
     */
    protected function printDependencyViolationErrors(string $moduleToValidate, ArrayObject $moduleDependencyTransferCollection, OutputInterface $output, ?string $dependencyType = null): void
    {
        $tableRows = [];
        foreach ($moduleDependencyTransferCollection as $moduleDependencyTransfer) {
            if ($dependencyType !== null && !in_array($dependencyType, $moduleDependencyTransfer->getDependencyTypes())) {
                continue;
            }

            foreach ($moduleDependencyTransfer->getValidationMessages() as $validationMessageTransfer) {
                $tableRows[] = [$moduleDependencyTransfer->getModule(), $validationMessageTransfer->getMessage()];
            }
        }

        $this->renderTable($output, [new TableCell(sprintf('Dependency violations in <fg=yellow>%s</>', $moduleToValidate), ['colspan' => 2])], $tableRows);
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $headers
     * @param array $rows
     *
     * @return void
     */
    protected function renderTable(OutputInterface $output, array $headers, array $rows): void
    {
        $table = new Table($output);
        $table->setHeaders($headers);
        $table->addRows($rows);
        $table->render();
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleDependencyTransfer $moduleDependencyTransfer
     *
     * @return array
     */
    protected function buildTableRow(ModuleDependencyTransfer $moduleDependencyTransfer): array
    {
        return [
            'Dependency Module' => sprintf('<fg=yellow>%s</>', $moduleDependencyTransfer->getModule()),
            'is valid' => $this->getColoredYesOrNo($moduleDependencyTransfer->getIsValid()),
            'src dependency' => $this->getYesOrNo($moduleDependencyTransfer->getIsSrcDependency()),
            'test dependency' => $this->getYesOrNo($moduleDependencyTransfer->getIsTestDependency()),
            'in composer require' => $this->getYesOrNo($moduleDependencyTransfer->getIsInComposerRequire()),
            'in composer require-dev' => $this->getYesOrNo($moduleDependencyTransfer->getIsInComposerRequireDev()),
            'in composer suggest' => $this->getYesOrNo($moduleDependencyTransfer->getIsSuggested()),
            'is optional dependency' => $this->getYesOrNo($moduleDependencyTransfer->getIsOptionalDependency()),
            'is own extension module' => $this->getYesOrNo($moduleDependencyTransfer->getIsOwnExtensionModule()),
            'Type(s)' => implode(', ', $moduleDependencyTransfer->getDependencyTypes()),
        ];
    }

    /**
     * @param bool $bool
     *
     * @return string
     */
    protected function getYesOrNo(bool $bool): string
    {
        return ($bool) ? 'yes' : 'no';
    }

    /**
     * @param bool $bool
     *
     * @return string
     */
    protected function getColoredYesOrNo(bool $bool): string
    {
        return ($bool) ? '<fg=green>yes</>' : '<fg=red>no</>';
    }

    /**
     * @param array $modulesToValidate
     * @param string|null $dependencyType
     *
     * @return void
     */
    protected function startValidation(array $modulesToValidate, ?string $dependencyType = null): void
    {
        $typeMessage = '';
        if ($dependencyType !== null) {
            $typeMessage = sprintf('<fg=yellow>%s</> ', $dependencyType);
        }
        $message = sprintf(
            'Checking %d %s for %sdependency issues.',
            count($modulesToValidate),
            (count($modulesToValidate) === 1) ? 'Module <fg=yellow>' . $this->buildModuleKey(current($modulesToValidate)) . '</>' : 'Modules',
            $typeMessage
        );
        if ($this->output->isVerbose()) {
            $this->info($message);
        }
    }

    /**
     * @return int
     */
    protected function endValidation(): int
    {
        $dependencyViolationCount = ($this->dependencyViolationCount > 0) ? sprintf('<fg=red>%d</>', $this->dependencyViolationCount) : sprintf('<fg=yellow>%d</>', $this->dependencyViolationCount);
        if ($dependencyViolationCount > 0 || $this->output->isVerbose()) {
            $this->info(sprintf('%s module dependency issues found', $dependencyViolationCount));
        }

        return $this->dependencyViolationCount > 0 ? static::CODE_ERROR : static::CODE_SUCCESS;
    }

    /**
     * @param string $moduleName
     *
     * @return void
     */
    protected function startModuleValidation(string $moduleName): void
    {
        if ($this->output->isVerbose()) {
            $this->info(sprintf('Check dependencies in <fg=yellow>%s</> module', $moduleName));
        }
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param int $moduleViolationCount
     * @param string|null $dependencyType
     *
     * @return void
     */
    protected function endModuleValidation(OutputInterface $output, int $moduleViolationCount, ?string $dependencyType = null): void
    {
        $type = '';
        if ($dependencyType !== null) {
            $type = sprintf('<fg=magenta>%s</> ', $dependencyType);
        }

        if ($this->output->isVerbose()) {
            $this->info(sprintf('Found <fg=yellow>%s</> %sdependency violations', $moduleViolationCount, $type));
            $output->writeln('');
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return mixed
     */
    protected function getDependencyType(InputInterface $input)
    {
        return $input->getOption(static::OPTION_DEPENDENCY_TYPE);
    }
}
