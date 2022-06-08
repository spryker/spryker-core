<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeStyleSniffer;

use Laminas\Config\Reader\Xml;
use Laminas\Filter\FilterChain;
use Laminas\Filter\StringToLower;
use Laminas\Filter\Word\CamelCaseToDash;
use RuntimeException;
use Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface;
use Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationLoaderInterface;
use Spryker\Zed\Development\Business\Exception\CodeStyleSniffer\PathDoesNotExistException;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

class CodeStyleSniffer
{
    /**
     * @var int
     */
    protected const CODE_SUCCESS = 0;

    /**
     * @var string
     */
    protected const OPTION_IGNORE = 'ignore';

    /**
     * @var array<string>
     */
    protected const APPLICATION_NAMESPACES = ['Orm'];

    /**
     * @var array<string>
     */
    protected const APPLICATION_LAYERS = ['Zed', 'Client', 'Yves', 'Service', 'Shared'];

    /**
     * @var array<string>
     */
    protected const EXTENSIONS = ['php'];

    /**
     * @var string
     */
    protected const NAMESPACE_SPRYKER_SHOP = 'SprykerShop';

    /**
     * @var string
     */
    protected const NAMESPACE_SPRYKER = 'Spryker';

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @var array <string, mixed>
     */
    protected $options = [];

    /**
     * @var \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationLoaderInterface
     */
    protected $codeStyleSnifferConfigurationLoader;

    /**
     * @var int
     */
    protected $countResolvedPaths = 0;

    /**
     * @var int
     */
    protected $countTotalPaths = 0;

    /**
     * @var array<string>
     */
    protected $commandsToFix = [];

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     * @param \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationLoaderInterface $codeStyleSnifferConfigurationLoader
     */
    public function __construct(DevelopmentConfig $config, CodeStyleSnifferConfigurationLoaderInterface $codeStyleSnifferConfigurationLoader)
    {
        $this->config = $config;
        $this->codeStyleSnifferConfigurationLoader = $codeStyleSnifferConfigurationLoader;
    }

    /**
     * @param string|null $module
     * @param array<string, mixed> $options
     *
     * @return int
     */
    public function checkCodeStyle(?string $module, array $options = []): int
    {
        $resultCode = static::CODE_SUCCESS;

        $namespace = null;
        if (strpos($module, '.') !== false) {
            [$namespace, $module] = explode('.', $module, 2);
        }

        $pathOption = $options['path'] ?? null;
        $defaults = [
            static::OPTION_IGNORE => $namespace || $pathOption ? null : 'vendor/',
        ];
        $options += $defaults;

        $paths = $this->resolvePaths($module, $namespace, $pathOption, $options);
        $this->countTotalPaths = count($paths);

        foreach ($paths as $path => $codeStyleSnifferConfiguration) {
            $this->countResolvedPaths++;
            $resultCode |= $this->runSnifferCommand($path, $codeStyleSnifferConfiguration);
        }

        if ($this->commandsToFix) {
            echo 'To fix run the following command: ' . PHP_EOL;
            echo implode('', $this->commandsToFix);
        }

        return $resultCode;
    }

    /**
     * @param string|null $module
     * @param string|null $namespace
     * @param string|null $path
     * @param array<string, mixed> $options
     *
     * @return array<string, \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface>
     */
    protected function resolvePaths(?string $module, ?string $namespace, ?string $path, array $options): array
    {
        $path = $path !== null ? trim($path, DIRECTORY_SEPARATOR) : null;

        if ($namespace) {
            return $this->resolveCorePath($module, $namespace, $path, $options);
        }

        if (!$module) {
            return $this->addPath([], $this->config->getPathToRoot() . $path, $options);
        }

        return $this->resolveProjectPath($module, $path, $options);
    }

    /**
     * @param string $module
     * @param string $namespace
     * @param string|null $path
     * @param array<string, mixed> $options
     *
     * @return array<string, \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface>
     */
    protected function resolveCorePath(string $module, string $namespace, ?string $path, array $options)
    {
        if ($module === 'all') {
            return $this->getPathsToAllCoreModules($namespace, $path, $options);
        }

        return $this->getPathToCoreModule($module, $namespace, $path, $options);
    }

    /**
     * @param string $namespace
     * @param string|null $pathSuffix
     * @param array<string, mixed> $options
     *
     * @throws \RuntimeException
     *
     * @return array<string, \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface>
     */
    protected function getPathsToAllCoreModules(string $namespace, ?string $pathSuffix, array $options): array
    {
        if ($pathSuffix) {
            throw new RuntimeException('Path suffix option is not possible for "all".');
        }

        $pathToInternalNamespace = $this->config->getPathToInternalNamespace($namespace);

        if (!$pathToInternalNamespace) {
            throw new RuntimeException('Namespace invalid: ' . $namespace);
        }

        $paths = [];
        $modules = $this->getCoreModules($pathToInternalNamespace);
        foreach ($modules as $module) {
            $path = $pathToInternalNamespace . $module . DIRECTORY_SEPARATOR;
            $paths = $this->addPath($paths, $path, $options, $namespace);
        }

        return $paths;
    }

    /**
     * @param string $module
     * @param string $namespace
     * @param string|null $pathSuffix
     * @param array<string, mixed> $options
     *
     * @throws \Spryker\Zed\Development\Business\Exception\CodeStyleSniffer\PathDoesNotExistException
     *
     * @return array<string, \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface>
     */
    protected function getPathToCoreModule(string $module, string $namespace, ?string $pathSuffix, array $options)
    {
        $path = $this->getCorePath($module, $namespace, $pathSuffix);

        if ($this->isPathValid($path)) {
            return $this->addPath([], $path, $options, $namespace);
        }

        $message = sprintf(
            'Could not find a valid path to your module "%s". Expected path "%s". Maybe there is a typo in the module name?',
            $module,
            $path,
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
        $pathToInternalNamespace = $this->config->getPathToInternalNamespace($namespace);
        if ($pathToInternalNamespace && is_dir($pathToInternalNamespace . $module)) {
            return $this->buildPath($pathToInternalNamespace . $module . DIRECTORY_SEPARATOR, $pathSuffix);
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
     * @param \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface $codeStyleSnifferConfiguration
     *
     * @return int Exit code
     */
    protected function runSnifferCommand($path, CodeStyleSnifferConfigurationInterface $codeStyleSnifferConfiguration)
    {
        $standard = $codeStyleSnifferConfiguration->getCodingStandard($path);
        $processConfig = '--standard=' . $standard;

        if ($codeStyleSnifferConfiguration->isVerbose()) {
            $processConfig .= ' -v';
        }

        if (!$codeStyleSnifferConfiguration->isQuiet()) {
            $processConfig .= ' -p';
        }

        if ($codeStyleSnifferConfiguration->isExplaining()) {
            $processConfig .= ' -e';
        }

        $optionSniffs = $codeStyleSnifferConfiguration->getSpecificSniffs();
        if ($optionSniffs) {
            $processConfig .= ' --sniffs=' . $optionSniffs;
        }

        $processConfig .= ' --extensions=' . implode(',', static::EXTENSIONS);

        $optionIgnore = $codeStyleSnifferConfiguration->getIgnoredPaths();

        $customPaths = [];
        $hasConfigFile = file_exists($path . DIRECTORY_SEPARATOR . 'phpcs.xml');
        if (!$hasConfigFile) {
            if (is_dir($path . 'src')) {
                $customPaths[] = $path . 'src/';
            }
            if (is_dir($path . 'tests')) {
                $customPaths[] = $path . 'tests/';
            }
            $optionIgnore .= ($optionIgnore ? ',' : '') . '/src/Generated/';
        }

        if ($optionIgnore) {
            $processConfig .= ' --ignore=' . $optionIgnore;
        }

        $processConfig .= ' ' . implode(' ', $customPaths);

        $optionVerbose = $codeStyleSnifferConfiguration->isVerbose();
        $optionFix = $codeStyleSnifferConfiguration->isFixing();

        if ($optionVerbose && !$optionFix) {
            $processConfig .= ' -s';
        }

        if (!$hasConfigFile || $this->hasLegacyConfiguration($path . DIRECTORY_SEPARATOR . 'phpcs.xml')) {
            $path = ' ' . $path;
        } else {
            $path = '';
        }

        $command = sprintf(
            'vendor/bin/%s %s%s',
            $optionFix ? 'phpcbf' : 'phpcs',
            $processConfig,
            $customPaths ? '' : $path,
        );

        $optionDryRun = $codeStyleSnifferConfiguration->isDryRun();

        if ($optionDryRun) {
            echo $command . PHP_EOL;

            return static::CODE_SUCCESS;
        }

        $process = new Process(explode(' ', $command), $this->config->getPathToRoot());
        $process->setTimeout($this->config->getProcessTimeout());

        if (strpos($codeStyleSnifferConfiguration->getModule(), '.all') !== false) {
            return $this->runSnifferCommandForAll($process, $path, $codeStyleSnifferConfiguration);
        }

        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        return $process->getExitCode();
    }

    /**
     * @param \Symfony\Component\Process\Process $process
     * @param string $path
     * @param \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface $codeStyleSnifferConfiguration
     *
     * @return int
     */
    protected function runSnifferCommandForAll(
        Process $process,
        string $path,
        CodeStyleSnifferConfigurationInterface $codeStyleSnifferConfiguration
    ): int {
        $process->run();

        echo sprintf(
            'Finished %s/%s %s (level %s) /%s %s' . PHP_EOL,
            $this->countResolvedPaths,
            $this->countTotalPaths,
            basename($path),
            $codeStyleSnifferConfiguration->getLevel(),
            $this->getSnifferResultMessage($process),
            ($process->getExitCode() !== static::CODE_SUCCESS ? $process->getOutput() : ''),
        );

        if ($process->getExitCode() !== static::CODE_SUCCESS && !$codeStyleSnifferConfiguration->isFixing()) {
            $this->commandsToFix[] = sprintf('vendor/bin/console c:s:s -m %s.%s -f' . PHP_EOL, $codeStyleSnifferConfiguration->getNamespace(), basename($path));
        }

        return $process->getExitCode();
    }

    /**
     * @param \Symfony\Component\Process\Process $process
     *
     * @return string
     */
    protected function getSnifferResultMessage(Process $process): string
    {
        return sprintf('%s', $process->getExitCode() === static::CODE_SUCCESS ? "\033[32m OK \033[0m" : "\033[31m FAIL \033[0m");
    }

    /**
     * @param string $module
     * @param string|null $pathSuffix
     * @param array<string, mixed> $options
     *
     * @return array<string, \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface>
     */
    protected function resolveProjectPath(string $module, ?string $pathSuffix, array $options): array
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

        return $this->addPath([], implode(' ', $paths), $options);
    }

    /**
     * @param string $path
     *
     * @return array<string>
     */
    protected function getCoreModules(string $path): array
    {
        /** @var array<\Symfony\Component\Finder\SplFileInfo> $directories */
        $directories = (new Finder())
            ->directories()
            ->in($path)
            ->depth('== 0')
            ->sortByName();

        $modules = [];
        foreach ($directories as $dir) {
            $modules[] = $dir->getFilename();
        }

        return $modules;
    }

    /**
     * @param array<string, \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface> $paths
     * @param string $moduleDirectoryPath
     * @param array<string, mixed> $options
     * @param string|null $namespace
     *
     * @return array<string, \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface>
     */
    protected function addPath(array $paths, string $moduleDirectoryPath, array $options, ?string $namespace = null): array
    {
        $paths[$moduleDirectoryPath] = clone $this->codeStyleSnifferConfigurationLoader->load($options, $moduleDirectoryPath, $namespace);

        return $paths;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function hasLegacyConfiguration(string $path): bool
    {
        $xml = (new Xml())->fromFile($path);

        return empty($xml['file']);
    }
}
