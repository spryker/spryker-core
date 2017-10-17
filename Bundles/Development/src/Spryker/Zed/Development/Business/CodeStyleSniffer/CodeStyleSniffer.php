<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeStyleSniffer;

use Spryker\Zed\Development\Business\Exception\CodeStyleSniffer\PathDoesNotExistException;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Process\Process;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\CamelCaseToDash;
use Zend\Filter\Word\DashToCamelCase;
use Zend\Filter\Word\UnderscoreToCamelCase;

class CodeStyleSniffer
{
    const CODE_SUCCESS = 0;

    const OPTION_FIX = 'fix';
    const OPTION_PRINT_DIFF_REPORT = 'report-diff';
    const OPTION_DRY_RUN = 'dry-run';
    const OPTION_QUIET = 'quiet';
    const OPTION_EXPLAIN = 'explain';
    const OPTION_SNIFFS = 'sniffs';
    const OPTION_VERBOSE = 'verbose';

    const APPLICATION_LAYERS = ['Zed', 'Client', 'Yves', 'Service', 'Shared'];

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(DevelopmentConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string|null $module
     * @param array $options
     *
     * @return int
     */
    public function checkCodeStyle($module, array $options = [])
    {
        $isCore = isset($options['core']) ? $options['core'] : false;
        $pathOption = isset($options['path']) ? $options['path'] : null;
        $defaults = [
            'ignore' => $isCore || $pathOption ? '' : 'vendor/',
        ];
        $options += $defaults;

        $path = $this->resolvePath($module, $isCore, $pathOption);

        return $this->runSnifferCommand($path, $options);
    }

    /**
     * @param string $module
     * @param bool $isCore
     * @param string|null $path
     *
     * @return string
     */
    protected function resolvePath($module, $isCore, $path = null)
    {
        $path = $path !== null ? trim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : null;

        if ($isCore) {
            if (!$module) {
                return rtrim($this->config->getPathToCore(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }

            return $this->getPathToModule($module, $path);
        }

        $pathToRoot = $this->config->getPathToRoot();

        if (!$module) {
            return $pathToRoot . $path;
        }

        return $this->resolveProjectPath($module, $path);
    }

    /**
     * @param string $module
     * @param string|null $pathSuffix
     *
     * @throws \Spryker\Zed\Development\Business\Exception\CodeStyleSniffer\PathDoesNotExistException
     *
     * @return string
     */
    protected function getPathToModule($module, $pathSuffix = null)
    {
        $lookupPaths = $this->buildPaths($module, $pathSuffix);

        foreach ($lookupPaths as $path) {
            if ($this->isPathValid($path)) {
                return $path;
            }
        }

        $message = sprintf(
            'Could not find valid paths to your module "%s". Lookup paths "%s". Maybe there is a typo in the module name?',
            $module,
            implode(', ', $lookupPaths)
        );

        throw new PathDoesNotExistException($message);
    }

    /**
     * @param string $module
     * @param string|null $pathSuffix
     *
     * @return array
     */
    protected function buildPaths($module, $pathSuffix = null)
    {
        return [
            $this->getPathToCoreModule($this->normalizeModuleNameForSplit($module), $pathSuffix),
            $this->getPathToCorePackageNonSplit($this->normalizeModuleNameForSplit($module), $pathSuffix),
            $this->getPathToCoreModule($this->normalizeModuleNameForNonSplit($module), $pathSuffix),
        ];
    }

    /**
     * @param string $module
     *
     * @return string
     */
    protected function normalizeModuleNameForNonSplit($module)
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new UnderscoreToCamelCase())
            ->attach(new DashToCamelCase());

        return ucfirst($filterChain->filter($module));
    }

    /**
     * @param string $module
     *
     * @return string
     */
    protected function normalizeModuleNameForSplit($module)
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new UnderscoreToCamelCase())
            ->attach(new CamelCaseToDash());

        return strtolower($filterChain->filter($module));
    }

    /**
     * @param string $module
     * @param string|null $pathSuffix
     *
     * @return string
     */
    protected function getPathToCoreModule($module, $pathSuffix = null)
    {
        return implode('', [
            rtrim($this->config->getPathToCore(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR,
            $module . DIRECTORY_SEPARATOR,
            $pathSuffix,
        ]);
    }

    /**
     * @param string $module
     * @param string|null $pathSuffix
     *
     * @return string
     */
    protected function getPathToCorePackageNonSplit($module, $pathSuffix = null)
    {
        return implode('', [
            rtrim(dirname(dirname($this->config->getPathToCore())), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR,
            $module . DIRECTORY_SEPARATOR,
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

        $config = ' --standard=' . $this->config->getCodingStandard();
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

        if ($options[static::OPTION_VERBOSE] && !$options[static::OPTION_FIX]) {
            $config .= ' -s';
        }

        $command = $options[static::OPTION_FIX] ? 'phpcbf' : 'phpcs';
        $command = 'vendor/bin/' . $command . ' ' . $pathToFiles . $config;

        if (!empty($options[static::OPTION_DRY_RUN])) {
            echo $command . PHP_EOL;

            return static::CODE_SUCCESS;
        }

        $process = new Process($command, $this->config->getPathToRoot(), null, null, 4800);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        return $process->getExitCode();
    }

    /**
     * @param string $module
     * @param string|null $pathSuffix
     *
     * @return string
     */
    protected function resolveProjectPath($module, $pathSuffix = null)
    {
        $projectNamespaces = $this->config->getProjectNamespaces();
        $pathToRoot = $this->config->getPathToRoot();

        $paths = [];
        foreach ($projectNamespaces as $projectNamespace) {
            $path = $pathToRoot . 'src' . DIRECTORY_SEPARATOR . $projectNamespace . DIRECTORY_SEPARATOR;

            foreach (static::APPLICATION_LAYERS as $layer) {
                $layerPath = $path . $layer . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR;
                if ($pathSuffix) {
                    $layerPath .= $pathSuffix;
                }

                if (!is_dir($layerPath)) {
                    continue;
                }

                $paths[] = $layerPath;
            }
        }

        return implode(' ', $paths);
    }
}
