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
    protected $pathToBundles;

    /**
     * @var \Spryker\Zed\Development\Business\Codeception\Argument\Builder\CodeceptionArgumentsBuilderInterface
     */
    protected $argumentBuilder;

    /**
     * @param string $applicationRoot
     * @param string $pathToBundles
     * @param \Spryker\Zed\Development\Business\Codeception\Argument\Builder\CodeceptionArgumentsBuilderInterface $argumentBuilder
     */
    public function __construct(
        string $applicationRoot,
        string $pathToBundles,
        CodeceptionArgumentsBuilderInterface $argumentBuilder
    ) {
        $this->applicationRoot = $applicationRoot;
        $this->pathToBundles = $pathToBundles;
        $this->argumentBuilder = $argumentBuilder;
    }

    /**
     * @param string|null $bundle
     * @param array $options
     *
     * @throws \ErrorException
     *
     * @return void
     */
    public function runTest($bundle, array $options = [])
    {
        $path = $this->resolvePath($bundle);

        if (!is_dir($path)) {
            $message = 'This path does not exist';
            if (!empty($bundle)) {
                $message = 'This bundle does not exist';
            }

            throw new ErrorException($message);
        }

        $this->runTestCommand($path, $options);
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
        return $this->pathToBundles . $bundle . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $path
     * @param array $options
     *
     * @return void
     */
    protected function runTestCommand($path, array $options)
    {
        $commandArguments = $this->argumentBuilder
            ->build($options)
            ->asString();

        if ($options[static::OPTION_INITIALIZE]) {
            $this->initializeTestCommand($options);

            echo 'Test classes generated.';
        }

        $command = 'vendor/bin/codecept run' . $commandArguments;

        $process = new Process($command, $this->applicationRoot, null, null, 4800);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

    /**
     * @param array $options
     *
     * @return void
     */
    protected function initializeTestCommand(array $options): void
    {
        $command = 'vendor/bin/codecept build';

        $process = new Process($command, $this->applicationRoot, null, null, 4800);
        $process->run(function ($type, $buffer) use ($options) {
            if ($options[static::OPTION_VERBOSE]) {
                echo $buffer;
            }
        });
    }
}
