<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeTest;

use Generated\Shared\Transfer\ModuleFilterTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use RuntimeException;
use Spryker\Zed\Development\Business\Codeception\Argument\Builder\CodeceptionArgumentsBuilderInterface;
use Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeInterface;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Process\Process;

class CodeTester
{
    public const OPTION_VERBOSE = 'verbose';

    public const OPTION_INITIALIZE = 'initialize';

    public const OPTION_GROUP = 'group';

    public const OPTION_TYPE_EXCLUDE = 'exclude';

    public const OPTION_DRY_RUN = 'dry-run';

    protected const CODE_SUCCESS = 0;
    protected const CODE_ERROR = 1;

    /**
     * @var \Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeInterface
     */
    protected $developmentToModuleFinderFacade;

    /**
     * @var \Spryker\Zed\Development\Business\Codeception\Argument\Builder\CodeceptionArgumentsBuilderInterface
     */
    protected $argumentBuilder;

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeInterface $developmentToModuleFinderFacade
     * @param \Spryker\Zed\Development\Business\Codeception\Argument\Builder\CodeceptionArgumentsBuilderInterface $argumentBuilder
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(
        DevelopmentToModuleFinderFacadeInterface $developmentToModuleFinderFacade,
        CodeceptionArgumentsBuilderInterface $argumentBuilder,
        DevelopmentConfig $config
    ) {
        $this->developmentToModuleFinderFacade = $developmentToModuleFinderFacade;
        $this->argumentBuilder = $argumentBuilder;
        $this->config = $config;
    }

    /**
     * Runs `vendor/bin/codecept run`.
     * If module is given, it will run over this (core) module. Otherwise runs over project level.
     *
     * @param string|null $moduleName
     * @param array $options
     *
     * @throws \RuntimeException
     *
     * @return int
     */
    public function runTest(?string $moduleName, array $options = []): int
    {
        if (!$moduleName) {
            if ($options[static::OPTION_INITIALIZE] && !$options[static::OPTION_DRY_RUN]) {
                $this->runCodeceptionBuild($options);
            }

            return $this->runTestCommand(null, $options);
        }

        $moduleFilterTransfer = $this->buildModuleFilterTransfer($moduleName);
        $modules = $this->developmentToModuleFinderFacade->getModules($moduleFilterTransfer);
        if (!$modules) {
            throw new RuntimeException('No matching core modules found.');
        }

        $result = static::CODE_SUCCESS;
        foreach ($modules as $module) {
            $path = $module->getPath();

            if (!$this->runTestCommand($path, $options)) {
                $result = static::CODE_ERROR;
            }
        }

        return $result;
    }

    /**
     * Runs `vendor/bin/codecept fixtures`.
     * If module is given, it will run over this (core) module. Otherwise runs over project level.
     *
     * @param string|null $moduleName
     * @param array $options
     *
     * @throws \RuntimeException
     *
     * @return int
     */
    public function runFixtures(?string $moduleName, array $options = []): int
    {
        if (!$moduleName) {
            if ($options[static::OPTION_INITIALIZE] && !$options[static::OPTION_DRY_RUN]) {
                $this->runCodeceptionBuild($options);
            }

            return $this->runFixturesCommand(null, $options);
        }

        $moduleFilterTransfer = $this->buildModuleFilterTransfer($moduleName);
        $modules = $this->developmentToModuleFinderFacade->getModules($moduleFilterTransfer);
        if (!$modules) {
            throw new RuntimeException('No matching core modules found.');
        }

        $result = static::CODE_SUCCESS;
        foreach ($modules as $module) {
            $path = $module->getPath();

            if (!$this->runFixturesCommand($path, $options)) {
                $result = static::CODE_ERROR;
            }
        }

        return $result;
    }

    /**
     * @param string|null $moduleName
     *
     * @return \Generated\Shared\Transfer\ModuleFilterTransfer
     */
    protected function buildModuleFilterTransfer(?string $moduleName): ModuleFilterTransfer
    {
        $moduleFilterTransfer = new ModuleFilterTransfer();
        if ($moduleName === null) {
            return $moduleFilterTransfer;
        }

        if (strpos($moduleName, '.') === false) {
            $moduleTransfer = new ModuleTransfer();
            $moduleTransfer->setName($moduleName);
            $moduleFilterTransfer->setModule($moduleTransfer);

            return $moduleFilterTransfer;
        }

        return $this->addFilterDetails($moduleName, $moduleFilterTransfer);
    }

    /**
     * @param string $moduleName
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer $moduleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleFilterTransfer
     */
    protected function addFilterDetails(string $moduleName, ModuleFilterTransfer $moduleFilterTransfer): ModuleFilterTransfer
    {
        $moduleFragments = explode('.', $moduleName);

        $organization = array_shift($moduleFragments);
        $moduleName = array_shift($moduleFragments);

        if ($moduleName === null) {
            $moduleName = $organization;
            $organization = null;
        }

        $moduleTransfer = new ModuleTransfer();
        $moduleTransfer->setName($moduleName);
        $moduleFilterTransfer->setModule($moduleTransfer);

        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer->setName($organization);
        $moduleFilterTransfer->setOrganization($organizationTransfer);

        return $moduleFilterTransfer;
    }

    /**
     * @param string|null $path
     * @param array $options
     *
     * @return int
     */
    protected function runTestCommand(?string $path, array $options): int
    {
        $commandLine = [];

        $commandLine[] = 'vendor/bin/codecept';
        $commandLine[] = 'run';

        if ($path) {
            $options['config'] = $path;
        }

        $commandLine = array_merge(
            $commandLine,
            $this->argumentBuilder
                ->build($options)
                ->getArguments()
        );

        if ($options[static::OPTION_DRY_RUN]) {
            return $this->dryRun($commandLine);
        }

        $process = new Process($commandLine, $this->config->getPathToRoot(), null, null, $this->config->getProcessTimeout());
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        return $process->getExitCode();
    }

    /**
     * @param string|null $path
     * @param array $options
     *
     * @return int
     */
    protected function runFixturesCommand(?string $path, array $options): int
    {
        if ($options[static::OPTION_INITIALIZE] && !$options[static::OPTION_DRY_RUN]) {
            $this->runCodeceptionBuild($options);
        }

        $commandLine = [];

        $commandLine[] = 'vendor/bin/codecept';
        $commandLine[] = 'fixtures';

        $commandLine = array_merge(
            $commandLine,
            $this->argumentBuilder
                ->build($options)
                ->getArguments()
        );

        $process = new Process($commandLine, $this->config->getPathToRoot(), null, null, $this->config->getProcessTimeout());
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        return $process->getExitCode();
    }

    /**
     * @param array $options
     *
     * @return void
     */
    protected function runCodeceptionBuild(array $options): void
    {
        $commandLine = [];

        $commandLine[] = 'vendor/bin/codecept';
        $commandLine[] = 'build';

        $process = new Process($commandLine, $this->config->getPathToRoot(), null, null, $this->config->getProcessTimeout());
        $process->run(function ($type, $buffer) use ($options) {
            if ($options[static::OPTION_VERBOSE]) {
                echo $buffer;
            }
        });
    }

    /**
     * @param string[] $command
     *
     * @return int
     */
    protected function dryRun(array $command): int
    {
        $output = [];
        foreach ($command as $line) {
            if (strpos($line, ' ') !== false) {
                $line = '"' . $line . '"';
            }
            $output[] = $line;
        }
        echo implode(' ', $output) . PHP_EOL;

        return static::CODE_SUCCESS;
    }
}
