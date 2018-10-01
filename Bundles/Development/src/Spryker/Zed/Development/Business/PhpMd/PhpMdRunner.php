<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\PhpMd;

use ErrorException;
use Symfony\Component\Process\Process;
use Zend\Filter\Word\UnderscoreToCamelCase;

class PhpMdRunner
{
    public const CODE_SUCCESS = 0;

    public const BUNDLE_ALL = 'all';

    public const OPTION_DRY_RUN = 'dry-run';
    public const OPTION_FORMAT = 'format';

    /**
     * @var string
     */
    protected $applicationRoot;

    /**
     * @var string
     */
    protected $pathToBundles;

    /**
     * @var string
     */
    protected $architectureStandard;

    /**
     * @param string $applicationRoot
     * @param string $pathToBundles
     * @param string $architectureStandard
     */
    public function __construct($applicationRoot, $pathToBundles, $architectureStandard)
    {
        $this->applicationRoot = $applicationRoot;
        $this->pathToBundles = $pathToBundles;
        $this->architectureStandard = $architectureStandard;
    }

    /**
     * @param string|null $bundle
     * @param array $options
     *
     * @throws \ErrorException
     *
     * @return int Exit code
     */
    public function run($bundle, array $options = [])
    {
        $path = $this->resolvePath($bundle);

        if (!is_dir($path)) {
            $message = 'This path does not exist';
            if (!empty($bundle)) {
                $message = 'This bundle does not exist';
            }

            throw new ErrorException($message);
        }

        $defaults = [
            'ignore' => $bundle ? '' : 'vendor/',
        ];
        $options += $defaults;

        return $this->runPhpMdCommand($path, $options);
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
    protected function resolvePath($bundle)
    {
        if ($bundle) {
            if ($bundle === static::BUNDLE_ALL) {
                return $this->pathToBundles;
            }

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
    protected function getPathToBundle($bundle)
    {
        return $this->pathToBundles . $bundle . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $path
     * @param array $options
     *
     * @return int Exit code
     */
    protected function runPhpMdCommand($path, array $options)
    {
        $pathToFiles = rtrim($path, DIRECTORY_SEPARATOR);

        $format = 'text';
        if ($options[static::OPTION_FORMAT]) {
            $format = $options[static::OPTION_FORMAT];
        }

        $config = $this->architectureStandard;

        if ($options['ignore']) {
            $config .= ' --exclude ' . $options['ignore'];
        }

        $command = 'vendor/bin/phpmd ' . $pathToFiles . ' ' . $format . ' ' . $config;
        if (!empty($options[static::OPTION_DRY_RUN])) {
            echo $command . PHP_EOL;

            return static::CODE_SUCCESS;
        }

        $process = new Process($command, $this->applicationRoot, null, null, 4800);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        return $process->getExitCode();
    }
}
