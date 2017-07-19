<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeStyleSniffer;

use Spryker\Zed\Development\Business\Exception\CodeStyleSniffer\PathDoesNotExistException;
use Symfony\Component\Process\Process;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\CamelCaseToDash;
use Zend\Filter\Word\DashToCamelCase;
use Zend\Filter\Word\UnderscoreToCamelCase;

class CodeStyleSniffer
{

    const CODE_SUCCESS = 0;

    const BUNDLE_ALL = 'all';

    const OPTION_FIX = 'fix';
    const OPTION_PRINT_DIFF_REPORT = 'report-diff';
    const OPTION_DRY_RUN = 'dry-run';
    const OPTION_QUIET = 'quiet';
    const OPTION_EXPLAIN = 'explain';
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
     * @return int
     */
    public function checkCodeStyle($bundle, array $options = [])
    {
        $path = isset($options['path']) ? $options['path'] : null;
        $path = $this->resolvePath($bundle, $path);

        $defaults = [
            'ignore' => $bundle ? '' : 'vendor/',
        ];
        $options += $defaults;

        return $this->runSnifferCommand($path, $options);
    }

    /**
     * @param string $bundle
     * @param string|null $path
     *
     * @return string
     */
    protected function resolvePath($bundle, $path = null)
    {
        $path = $path !== null ? trim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : null;

        if ($bundle) {
            if (strtolower($bundle) === static::BUNDLE_ALL) {
                return rtrim($this->pathToBundles, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }

            return $this->getPathToBundle($bundle, $path);
        }

        return rtrim($this->applicationRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $path;
    }

    /**
     * @param string $bundle
     * @param string|null $pathSuffix
     *
     * @throws \Spryker\Zed\Development\Business\Exception\CodeStyleSniffer\PathDoesNotExistException
     *
     * @return string
     */
    protected function getPathToBundle($bundle, $pathSuffix = null)
    {
        $lookupPaths = $this->buildPaths($bundle, $pathSuffix);

        foreach ($lookupPaths as $path) {
            if ($this->isPathValid($path)) {
                return $path;
            }
        }

        $message = sprintf(
            'Could not find valid paths to your module "%s". Lookup paths "%s". Maybe there is a typo in the module name?',
            $bundle,
            implode(', ', $lookupPaths)
        );

        throw new PathDoesNotExistException($message);
    }

    /**
     * @param string $bundle
     * @param string|null $pathSuffix
     *
     * @return array
     */
    protected function buildPaths($bundle, $pathSuffix = null)
    {
        return [
            $this->getPathToSprykerBundle($this->normalizeBundleNameForSplit($bundle), $pathSuffix),
            $this->getPathToSprykerPackageNonSplit($this->normalizeBundleNameForSplit($bundle), $pathSuffix),
            $this->getPathToSprykerBundle($this->normalizeBundleNameForNonSplit($bundle), $pathSuffix),
        ];
    }

    /**
     * @param string $bundle
     *
     * @return string
     */
    protected function normalizeBundleNameForNonSplit($bundle)
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new UnderscoreToCamelCase())
            ->attach(new DashToCamelCase());

        return ucfirst($filterChain->filter($bundle));
    }

    /**
     * @param string $bundle
     *
     * @return string
     */
    protected function normalizeBundleNameForSplit($bundle)
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new UnderscoreToCamelCase())
            ->attach(new CamelCaseToDash());

        return strtolower($filterChain->filter($bundle));
    }

    /**
     * @param string $bundle
     * @param string|null $pathSuffix
     *
     * @return string
     */
    protected function getPathToSprykerBundle($bundle, $pathSuffix = null)
    {
        return implode('', [
            rtrim($this->pathToBundles, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR,
            $bundle . DIRECTORY_SEPARATOR,
            $pathSuffix,
        ]);
    }

    /**
     * @param string $bundle
     * @param string|null $pathSuffix
     *
     * @return string
     */
    protected function getPathToSprykerPackageNonSplit($bundle, $pathSuffix = null)
    {
        return implode('', [
            rtrim(dirname(dirname($this->pathToBundles)), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR,
            $bundle . DIRECTORY_SEPARATOR,
            $pathSuffix,
        ]);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function isPathValid($path)
    {
        return (is_file($path) || is_dir($path));
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
        if ($options[static::OPTION_VERBOSE]) {
            $config .= ' -v';
        }
        if (!$options[static::OPTION_QUIET]) {
            $config .= ' -p'; // Progress
        }

        if ($options[static::OPTION_EXPLAIN]) {
            $config .= ' -e';
        }

        if ($options[static::OPTION_SNIFFS]) {
            $config .= ' --sniffs=' . $options[static::OPTION_SNIFFS];
        }

        if ($options['ignore']) {
            $config .= ' --ignore=' . $options['ignore'];
        }

        $command = $options[static::OPTION_FIX] ? 'phpcbf' : 'phpcs';
        $command = 'vendor/bin/' . $command . ' ' . $pathToFiles . $config;

        if (!empty($options[static::OPTION_DRY_RUN])) {
            echo $command;

            return static::CODE_SUCCESS;
        }

        $process = new Process($command, $this->applicationRoot, null, null, 4800);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        return $process->getExitCode();
    }

    /**
     * @deprecated Use `normalizeBundleNameForNonSplit()` or `normalizeBundleNameForSplit()`
     *
     * @param string $bundle
     *
     * @return string
     */
    protected function normalizeBundleName($bundle)
    {
        $filter = new UnderscoreToCamelCase();

        return ucfirst($filter->filter($bundle));
    }

}
