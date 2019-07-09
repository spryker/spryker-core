<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeTest;

use ErrorException;
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
     * @param string $applicationRoot
     * @param string $pathToBundles
     */
    public function __construct($applicationRoot, $pathToBundles)
    {
        $this->applicationRoot = $applicationRoot;
        $this->pathToBundles = $pathToBundles;
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
        if ($options[static::OPTION_INITIALIZE]) {
            $this->runCodeceptionBuild($options);
        }

        $command = 'vendor/bin/codecept run' . $this->extractOptions($path, $options);

        $process = new Process($command, $this->applicationRoot, null, null, 4800);
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

        $command = 'vendor/bin/codecept fixtures' . $this->extractOptions($path, $options);

        $process = new Process($command, $this->applicationRoot, null, null, 4800);
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
     * @param string $path
     * @param array $options
     *
     * @return string
     */
    protected function extractOptions(string $path, array $options): string
    {
        $pathToFiles = rtrim($path, DIRECTORY_SEPARATOR);
        $config = [];

        if ($pathToFiles) {
            $config[] = '-c ' . $pathToFiles;
        }

        if ($options[static::OPTION_GROUP]) {
            $config[] = '-g ' . $options[static::OPTION_GROUP];
        }

        if ($options[static::OPTION_TYPE_EXCLUDE]) {
            $config[] = '-x ' . $options[static::OPTION_TYPE_EXCLUDE];
        }

        if ($options[static::OPTION_VERBOSE]) {
            $config[] = '-v';
        }

        return ' ' . implode(' ', $config);
    }

    /**
     * @param array $options
     *
     * @return void
     */
    protected function runCodeceptionBuild(array $options): void
    {
        $command = 'vendor/bin/codecept build';
        $process = new Process($command, $this->applicationRoot, null, null, 4800);
        $process->run(function ($type, $buffer) use ($options) {
            if ($options[static::OPTION_VERBOSE]) {
                echo $buffer;
            }
        });

        echo 'Test classes generated.';
    }
}
