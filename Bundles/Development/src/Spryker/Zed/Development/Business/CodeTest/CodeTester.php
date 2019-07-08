<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeTest;

use ErrorException;
use Spryker\Zed\Development\Business\Codeception\Argument\Builder\CodeceptionArgumentsBuilderInterface;
use Symfony\Component\Process\Process;
use Zend\Filter\Word\UnderscoreToCamelCase;

class CodeTester
{
    public const OPTION_VERBOSE = 'verbose';

    public const OPTION_INITIALIZE = 'initialize';

    public const OPTION_GROUP = 'group';

    public const OPTION_TYPE_EXCLUDE = 'exclude';

    /**
     * @var string
     */
    protected $applicationRoot;

    /**
     * @var string
     */
    protected $pathToModules;

    /**
     * @var \Spryker\Zed\Development\Business\Codeception\Argument\Builder\CodeceptionArgumentsBuilderInterface
     */
    protected $argumentBuilder;

    /**
     * @var int
     */
    protected $processTimeout;

    /**
     * @param string $applicationRoot
     * @param string $pathToModules
     * @param \Spryker\Zed\Development\Business\Codeception\Argument\Builder\CodeceptionArgumentsBuilderInterface $argumentBuilder
     * @param int $processTimeout
     */
    public function __construct(
        string $applicationRoot,
        string $pathToModules,
        CodeceptionArgumentsBuilderInterface $argumentBuilder,
        int $processTimeout
    ) {
        $this->applicationRoot = $applicationRoot;
        $this->pathToModules = $pathToModules;
        $this->argumentBuilder = $argumentBuilder;
        $this->processTimeout = $processTimeout;
    }

    /**
     * @param string|null $bundle
     * @param array $options
     *
     * @return void
     */
    public function runTest($bundle, array $options = [])
    {
        $path = $this->resolvePath($bundle);

        $this->assertPath($bundle, $path);
        $this->runTestCommand($path, $options);
    }

    /**
     * @param string|null $bundle
     * @param array $options
     *
     * @return void
     */
    public function runFixtures($bundle, array $options = [])
    {
        $path = $this->resolvePath($bundle);

        $this->assertPath($bundle, $path);
        $this->runFixturesCommand($path, $options);
    }

    /**
     * @param string $bundle
     *
     * @return string
     */
    protected function resolvePath($bundle)
    {
        if ($bundle) {
            $bundle = $this->normalizeBundleName($bundle);

            return $this->getPathToBundle($bundle);
        }

        return $this->applicationRoot;
    }

    /**
     * @param string $bundle
     *
     * @return string
     */
    protected function normalizeBundleName($bundle)
    {
        $filter = new UnderscoreToCamelCase();

        return ucfirst($filter->filter($bundle));
    }

    /**
     * @param string $bundle
     *
     * @return string
     */
    protected function getPathToBundle($bundle)
    {
        return $this->pathToModules . $bundle . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $path
     * @param array $options
     *
     * @return void
     */
    protected function runTestCommand($path, array $options)
    {
        if ($options[static::OPTION_INITIALIZE]) {
            $this->runCodeceptionBuild($options);
        }

        $commandLine = [];

        $commandLine[] = 'vendor/bin/codecept';
        $commandLine[] = 'run';

        $commandLine = array_merge(
            $commandLine,
            $this->argumentBuilder
                ->build($options)
                ->getArguments()
        );

        $process = new Process($commandLine, $this->applicationRoot, null, null, $this->processTimeout);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

    /**
     * @param string $path
     * @param array $options
     *
     * @return void
     */
    protected function runFixturesCommand($path, array $options)
    {
        if ($options[static::OPTION_INITIALIZE]) {
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

        $process = new Process($commandLine, $this->applicationRoot, null, null, $this->processTimeout);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

    /**
     * @param string|null $bundle
     * @param string $path
     *
     * @throws \ErrorException
     *
     * @return void
     */
    protected function assertPath($bundle, string $path): void
    {
        if (!is_dir($path)) {
            $message = 'This path does not exist';
            if (!empty($bundle)) {
                $message = 'This bundle does not exist';
            }

            throw new ErrorException($message);
        }
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

        $process = new Process($commandLine, $this->applicationRoot, null, null, $this->processTimeout);
        $process->run(function ($type, $buffer) use ($options) {
            if ($options[static::OPTION_VERBOSE]) {
                echo $buffer;
            }
        });
    }
}
