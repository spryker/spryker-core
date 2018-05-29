<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeStyleSniffer;

use RuntimeException;
use Spryker\Zed\Development\Business\Exception\CodeStyleSniffer\PathDoesNotExistException;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Process\Process;
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToDash;

class CodeStyleSniffer
{
    protected const CODE_SUCCESS = 0;

    protected const OPTION_FIX = 'fix';
    protected const OPTION_PRINT_DIFF_REPORT = 'report-diff';
    protected const OPTION_DRY_RUN = 'dry-run';
    protected const OPTION_QUIET = 'quiet';
    protected const OPTION_EXPLAIN = 'explain';
    protected const OPTION_SNIFFS = 'sniffs';
    protected const OPTION_VERBOSE = 'verbose';

    protected const APPLICATION_NAMESPACES = ['Orm'];
    protected const APPLICATION_LAYERS = ['Zed', 'Client', 'Yves', 'Service', 'Shared'];

    protected const NAMESPACE_SPRYKER_SHOP = 'SprykerShop';
    protected const NAMESPACE_SPRYKER = 'Spryker';

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
        $namespace = null;
        if (strpos($module, '.') !== false) {
            list ($namespace, $module) = explode('.', $module, 2);
        }

        $pathOption = isset($options['path']) ? $options['path'] : null;
        $defaults = [
            'ignore' => $namespace || $pathOption ? '' : 'vendor/',
        ];
        $options += $defaults;

        $path = $this->resolvePath($module, $namespace, $pathOption);

        return $this->runSnifferCommand($path, $options);
    }

    /**
     * @param string $module
     * @param string|null $namespace
     * @param string|null $path
     *
     * @return string
     */
    protected function resolvePath($module, $namespace = null, $path = null)
    {
        $path = $path !== null ? trim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : null;

        if ($namespace) {
            if ($module === 'all') {
                return $this->getPathToCore($namespace, $path);
            }

            return $this->getPathToModule($module, $namespace, $path);
        }

        $pathToRoot = $this->config->getPathToRoot();

        if (!$module) {
            return $pathToRoot . $path;
        }

        return $this->resolveProjectPath($module, $path);
    }

    /**
     * @param string $namespace
     * @param string $path
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    protected function getPathToCore($namespace, $path)
    {
        if ($path) {
            throw new RuntimeException('Path suffix option is not possible for "all".');
        }

        if ($namespace === static::NAMESPACE_SPRYKER_SHOP) {
            $corePath = $this->config->getPathToShop();
        } elseif ($namespace === static::NAMESPACE_SPRYKER) {
            $corePath = $this->config->getPathToCore();
        } else {
            throw new RuntimeException('Namespace invalid: ' . $namespace);
        }

        return $corePath;
    }

    /**
     * @param string $module
     * @param string $namespace
     * @param string|null $pathSuffix
     *
     * @throws \Spryker\Zed\Development\Business\Exception\CodeStyleSniffer\PathDoesNotExistException
     *
     * @return string
     */
    protected function getPathToModule($module, $namespace, $pathSuffix = null)
    {
        $path = $this->getCorePath($module, $namespace, $pathSuffix);
        if ($this->isPathValid($path)) {
            return $path;
        }

        $message = sprintf(
            'Could not find a valid path to your module "%s". Expected path "%s". Maybe there is a typo in the module name?',
            $module,
            $path
        );

        throw new PathDoesNotExistException($message);
    }

    /**
     * @param string $module
     * @param string $namespace
     * @param string|null $pathSuffix
     *
     * @return string
     */
    protected function getCorePath($module, $namespace, $pathSuffix = null)
    {
        if ($namespace === static::NAMESPACE_SPRYKER && is_dir($this->config->getPathToCore() . $module)) {
            return $this->buildPath($this->config->getPathToCore() . $module . DIRECTORY_SEPARATOR, $pathSuffix);
        }

        if ($namespace === static::NAMESPACE_SPRYKER_SHOP && is_dir($this->config->getPathToShop() . $module)) {
            return $this->buildPath($this->config->getPathToShop() . $module . DIRECTORY_SEPARATOR, $pathSuffix);
        }

        $vendor = $this->normalizeName($namespace);
        $module = $this->normalizeName($module);
        $path = $this->config->getPathToRoot() . 'vendor' . DIRECTORY_SEPARATOR . $vendor . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR;

        return $this->buildPath($path, $pathSuffix);
    }

    /**
     * @param string $path
     * @param string $suffix
     *
     * @return string
     */
    protected function buildPath($path, $suffix)
    {
        if (!$suffix) {
            return $path;
        }

        return $path . $suffix;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function normalizeName($name)
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new CamelCaseToDash())
            ->attach(new StringToLower());

        return $filterChain->filter($name);
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
        $namespaces = array_merge(static::APPLICATION_NAMESPACES, $projectNamespaces);
        $pathToRoot = $this->config->getPathToRoot();

        $paths = [];
        foreach ($namespaces as $namespace) {
            $path = $pathToRoot . 'src' . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR;

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
