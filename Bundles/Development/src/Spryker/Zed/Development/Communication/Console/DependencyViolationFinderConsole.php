<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use ArrayObject;
use Generated\Shared\Transfer\DependencyValidationRequestTransfer;
use Generated\Shared\Transfer\ModuleDependencyTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Filter\Word\DashToCamelCase;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Business\DevelopmentBusinessFactory getFactory()
 */
class DependencyViolationFinderConsole extends Console
{
    const COMMAND_NAME = 'dev:dependency:find';
    const ARGUMENT_MODULE = 'module';
    const OPTION_DEPENDENCY_TYPE = 'dependency-type';
    const OPTION_DEPENDENCY_TYPE_SHORT = 'd';

    /**
     * @var array
     */
    protected $moduleNames = [];

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
            ->addArgument(static::ARGUMENT_MODULE, InputArgument::OPTIONAL, 'Module to run checks for.')
            ->addOption(static::OPTION_DEPENDENCY_TYPE, static::OPTION_DEPENDENCY_TYPE_SHORT, InputOption::VALUE_REQUIRED, 'Runs only one specific dependency type check.')
            ->setDescription('Find dependency violations in the dependency tree (Spryker core dev only).');
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
        if ($this->isSingleModuleValidation($modulesToValidate) && !$this->isModuleNameValid($modulesToValidate)) {
            $output->writeln(sprintf('Requested module <fg=green>%s</> not found in current scope.', current($modulesToValidate)));

            return static::CODE_ERROR;
        }

        $dependencyType = $this->getDependencyType($input);

        $this->startValidation($modulesToValidate, $dependencyType);

        foreach ($modulesToValidate as $moduleToValidate) {
            $this->validateModule($moduleToValidate, $output, $dependencyType);
        }

        return $this->endValidation();
    }

    /**
     * @param string $moduleToValidate
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string|null $dependencyType
     *
     * @return void
     */
    protected function validateModule(string $moduleToValidate, OutputInterface $output, ?string $dependencyType = null): void
    {
        $this->startModuleValidation($moduleToValidate);

        $moduleDependencyTransferCollection = $this->getModuleDependencies($moduleToValidate, $dependencyType);

        if ($output->isVerbose()) {
            $this->describeDependencies($moduleDependencyTransferCollection, $output, $dependencyType);
        }

        $moduleViolationCount = $this->getDependencyViolationCount($moduleDependencyTransferCollection, $dependencyType);
        if ($moduleViolationCount > 0) {
            $this->printDependencyViolationErrors($moduleToValidate, $moduleDependencyTransferCollection, $output, $dependencyType);
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
     * @param string $moduleToValidate
     * @param string|null $dependencyType
     *
     * @return \Generated\Shared\Transfer\ModuleDependencyTransfer[]
     */
    protected function getModuleDependencies(string $moduleToValidate, ?string $dependencyType = null): ArrayObject
    {
        $dependencyValidationRequestTransfer = new DependencyValidationRequestTransfer();
        $dependencyValidationRequestTransfer->setModule($moduleToValidate);
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
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return array
     */
    protected function getModulesToCheckForViolations(InputInterface $input): array
    {
        $modules = $this->getModuleNames();
        $module = $input->getArgument(static::ARGUMENT_MODULE);
        if ($module) {
            $filter = new DashToCamelCase();
            $filteredModuleName = ucfirst($filter->filter($module));
            $modules = [$filteredModuleName];
        }

        return $modules;
    }

    /**
     * @return array
     */
    protected function getModuleNames(): array
    {
        if (!$this->moduleNames) {
            $this->moduleNames = $this->getFacade()->getAllModules();
        }

        return $this->moduleNames;
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
        $moduleNames = $this->getModuleNames();

        if (!in_array(current($modulesToValidate), $moduleNames)) {
            return false;
        }

        return true;
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
            (count($modulesToValidate) === 1) ? 'Module <fg=yellow>' . current($modulesToValidate) . '</>' : 'Modules',
            $typeMessage
        );
        $this->info($message);
    }

    /**
     * @return int
     */
    protected function endValidation(): int
    {
        $dependencyViolationCount = ($this->dependencyViolationCount > 0) ? sprintf('<fg=red>%d</>', $this->dependencyViolationCount) : sprintf('<fg=yellow>%d</>', $this->dependencyViolationCount);
        $this->info(sprintf('%s module dependency issues found', $dependencyViolationCount));

        return $this->dependencyViolationCount > 0 ? static::CODE_ERROR : static::CODE_SUCCESS;
    }

    /**
     * @param string $moduleToValidate
     *
     * @return void
     */
    protected function startModuleValidation(string $moduleToValidate): void
    {
        $this->info(sprintf('Check dependencies in <fg=yellow>%s</> module', $moduleToValidate));
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

        $this->info(sprintf('Found <fg=yellow>%s</> %sdependency violations', $moduleViolationCount, $type));
        $output->writeln('');
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
