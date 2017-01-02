<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeStyleSniffer;

use ErrorException;
use Symfony\Component\Process\Process;
use Zend\Filter\Word\UnderscoreToCamelCase;

class CodeStyleSniffer
{

    const CODE_SUCCESS = 0;

    const BUNDLE_ALL = 'all';

    const OPTION_FIX = 'fix';
    const OPTION_PRINT_DIFF_REPORT = 'report-diff';
    const OPTION_DRY_RUN = 'dry-run';
    const OPTION_SNIFFS = 'sniffs';
    const OPTION_VERBOSE = 'verbose';

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
    protected $codingStandard;

    /**
     * @param string $applicationRoot
     * @param string $pathToBundles
     * @param string $codingStandard
     */
    public function __construct($applicationRoot, $pathToBundles, $codingStandard)
    {
        $this->applicationRoot = $applicationRoot;
        $this->pathToBundles = $pathToBundles;
        $this->codingStandard = $codingStandard;
    }

    /**
     * @param string|null $bundle
     * @param array $options
     *
     * @throws \ErrorException
     *
     * @return int Exit code
     */
    public function checkCodeStyle($bundle, array $options = [])
    {
        $path = $this->resolvePath($bundle, $options['path']);

        if (!is_file($path) && !is_dir($path)) {
            $message = 'This path does not exist';
            if ($bundle) {
                $message .= ' in bundle ' . $bundle;
            }

            throw new ErrorException($message . ': ' . $path);
        }

        $defaults = [
            'ignore' => $bundle ? '' : 'vendor/',
        ];
        $options += $defaults;

        return $this->runSnifferCommand($path, $options);
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
     * @param string|null $path
     *
     * @return string
     */
    protected function resolvePath($bundle, $path = null)
    {
        if ($bundle) {
            if ($bundle === self::BUNDLE_ALL) {
                return $this->pathToBundles;
            }

            $bundle = $this->normalizeBundleName($bundle);

            return $this->getPathToBundle($bundle) . $path;
        }

        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        return $this->applicationRoot . $path;
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
    protected function runSnifferCommand($path, array $options)
    {
        $pathToFiles = rtrim($path, DIRECTORY_SEPARATOR);

        $config = ' --standard=' . $this->codingStandard;
        if ($options[self::OPTION_VERBOSE]) {
            $config .= ' -v';
        }

        if ($options[self::OPTION_SNIFFS]) {
            $config .= ' --sniffs=' . $options[self::OPTION_SNIFFS];
        }

        if ($options['ignore']) {
            $config .= ' --ignore=' . $options['ignore'];
        }

        $command = $options[self::OPTION_FIX] ? 'phpcbf' : 'phpcs';
        $command = 'vendor/bin/' . $command . ' ' . $pathToFiles . $config;

        if (!empty($options[self::OPTION_DRY_RUN])) {
            echo $command;

            return self::CODE_SUCCESS;
        }

        $process = new Process($command, $this->applicationRoot, null, null, 4800);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        return $process->getExitCode();
    }

}
