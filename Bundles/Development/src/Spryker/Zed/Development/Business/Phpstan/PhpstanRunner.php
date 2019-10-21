<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Phpstan;

use RuntimeException;
use SplFileInfo;
use Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileFinderInterface;
use Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileManagerInterface;
use Spryker\Zed\Development\Business\Traits\PathTrait;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToDash;

class PhpstanRunner implements PhpstanRunnerInterface
{
    use PathTrait;

    public const NAMESPACE_SPRYKER_SHOP = 'SprykerShop';
    public const NAMESPACE_SPRYKER = 'Spryker';

    public const DEFAULT_LEVEL = 'defaultLevel';
    public const MEMORY_LIMIT = '512M';
    public const CODE_SUCCESS = 0;
    public const CODE_ERROR = 0;

    public const OPTION_DRY_RUN = 'dry-run';
    public const OPTION_VERBOSE = 'verbose';
    public const OPTION_MODULE = 'module';

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileFinderInterface
     */
    protected $phpstanConfigFileFinder;

    /**
     * @var \Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileManagerInterface
     */
    protected $phpstanConfigFileManager;

    /**
     * @var int
     */
    protected $errorCount = 0;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     * @param \Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileFinderInterface $phpstanConfigFileFinder
     * @param \Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileManagerInterface $phpstanConfigFileManager
     */
    public function __construct(
        DevelopmentConfig $config,
        PhpstanConfigFileFinderInterface $phpstanConfigFileFinder,
        PhpstanConfigFileManagerInterface $phpstanConfigFileManager
    ) {
        $this->config = $config;
        $this->phpstanConfigFileFinder = $phpstanConfigFileFinder;
        $this->phpstanConfigFileManager = $phpstanConfigFileManager;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \RuntimeException
     *
     * @return int Exit code
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $module = $input->getOption(static::OPTION_MODULE);

        $message = 'Run PHPStan in PROJECT level';
        if ($module) {
            $message = 'Run PHPStan in module ' . $module;
        }

        $output->writeln($message);

        if ($module) {
            $paths = $this->getPaths($module);
        } else {
            $paths[$this->config->getPathToRoot()] = $this->config->getPathToRoot();
        }
        if (empty($paths)) {
            throw new RuntimeException('No path found for module ' . $module);
        }

        $resultCode = 0;
        $count = 0;
        $total = count($paths);
        $this->errorCount = 0;

        asort($paths);

        foreach ($paths as $path => $configFilePath) {
            $resultCode |= $this->runCommand($path, $configFilePath, $input, $output);
            $count++;
            if ($input->getOption(static::OPTION_VERBOSE)) {
                $output->writeln(sprintf('Finished %s/%s.', $count, $total));
            }
        }
        if ($this->getErrorCount()) {
            $output->writeln('<error>Total errors found: ' . $this->errorCount . '</error>');
        }

        return $resultCode;
    }

    /**
     * @return int
     */
    protected function getErrorCount(): int
    {
        return $this->errorCount;
    }

    /**
     * @param string $path
     * @param string $configFilePath
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int Exit code
     */
    protected function runCommand($path, $configFilePath, InputInterface $input, OutputInterface $output)
    {
        $command = 'php -d memory_limit=%s vendor/bin/phpstan analyze --no-progress -c %s %s -l %s';

        $defaultLevel = $this->getDefaultLevel($path, $configFilePath);
        $level = $input->getOption('level');
        if (preg_match('/^([+])(\d)$/', $level, $matches)) {
            $level = $defaultLevel + (int)$matches[2];
        } else {
            $level = (int)$level ?: $defaultLevel;
        }

        if (is_dir($path . 'src')) {
            $path .= 'src' . DIRECTORY_SEPARATOR;
        }

        $configFilePath .= $this->config->getPhpstanConfigFilename();

        $command = sprintf($command, static::MEMORY_LIMIT, $configFilePath, $path, $level);

        if ($input->getOption(static::OPTION_DRY_RUN)) {
            $output->writeln($command);

            return static::CODE_SUCCESS;
        }

        if ($output->isVerbose()) {
            $output->writeln(sprintf('Checking %s (level %s)', $path, $level));
        }

        $process = $this->getProcess($command);

        $processOutputBuffer = '';

        $process->run(function ($type, $buffer) use ($output, &$processOutputBuffer) {
            $this->addErrors($buffer);

            preg_match('#\[ERROR\] Found (\d+) error#i', $buffer, $matches);
            if (!$matches && !$output->isVeryVerbose()) {
                $processOutputBuffer .= $buffer;

                return;
            }

            $processOutputBuffer .= $buffer;
            $output->write($processOutputBuffer);
            $processOutputBuffer = '';
        });

        $processOutputBuffer = '';

        if ($this->phpstanConfigFileManager->isMergedConfigFile($configFilePath)) {
            $this->phpstanConfigFileManager->deleteConfigFile($configFilePath);
        }

        return $process->getExitCode();
    }

    /**
     * @param string $command
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess($command)
    {
        return new Process(explode(' ', $command), null, null, null, 0);
    }

    /**
     * @param string $module
     *
     * @return array
     */
    protected function getPaths($module)
    {
        if (strpos($module, '.') !== false) {
            $paths = $this->resolveCorePaths($module);
        } else {
            $paths = $this->resolveProjectPaths($module);
        }

        return $paths;
    }

    /**
     * @param string $module
     * @param string|null $pathSuffix
     *
     * @return array
     */
    protected function resolveProjectPaths($module, $pathSuffix = null)
    {
        $projectNamespaces = $this->config->getProjectNamespaces();
        $namespaces = array_merge(DevelopmentConfig::APPLICATION_NAMESPACES, $projectNamespaces);
        $pathToRoot = $this->config->getPathToRoot();

        $paths = [];
        foreach ($namespaces as $namespace) {
            $path = $pathToRoot . 'src' . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR;

            foreach (DevelopmentConfig::APPLICATIONS as $layer) {
                $layerPath = $path . $layer . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR;
                if ($pathSuffix) {
                    $layerPath .= $pathSuffix;
                }

                if (!is_dir($layerPath)) {
                    continue;
                }

                $paths[$layerPath] = null;
            }
        }

        return $paths;
    }

    /**
     * @param array $paths
     * @param string $moduleDirectoryPath
     * @param string|null $namespace
     *
     * @return array
     */
    protected function addPath(array $paths, string $moduleDirectoryPath, $namespace = null): array
    {
        $paths[$moduleDirectoryPath] = $this->getConfigFilePathByModuleDirectory($moduleDirectoryPath, $namespace);

        return $paths;
    }

    /**
     * @param string $moduleDirectoryPath
     * @param string|null $namespace
     *
     * @return string
     */
    protected function getConfigFilePathByModuleDirectory(string $moduleDirectoryPath, $namespace = null): string
    {
        $moduleConfigFile = $this->phpstanConfigFileFinder
            ->searchIn($moduleDirectoryPath);

        $vendorDirectoryPath = $this->getVendorPathByNamespace($namespace);

        $vendorConfigFile = $this->phpstanConfigFileFinder
            ->searchIn($vendorDirectoryPath);

        if ($moduleConfigFile && $vendorConfigFile) {
            return $this->phpstanConfigFileManager->merge(
                [$moduleConfigFile, $vendorConfigFile],
                $this->getConfigFilenameForMerge($moduleConfigFile)
            );
        }

        if ($moduleConfigFile) {
            return $moduleConfigFile->getPath() . DIRECTORY_SEPARATOR;
        }

        if ($vendorConfigFile) {
            return $vendorConfigFile->getPath() . DIRECTORY_SEPARATOR;
        }

        return $this->config->getPathToRoot();
    }

    /**
     * @param \SplFileInfo $moduleConfigFile
     *
     * @return string|null
     */
    protected function getConfigFilenameForMerge(SplFileInfo $moduleConfigFile): ?string
    {
        $filenameFromPath = mb_strtolower(
            implode(
                '_',
                array_slice(
                    explode('/', $moduleConfigFile->getPath()),
                    -3,
                    3
                )
            )
        );

        return $filenameFromPath . '_';
    }

    /**
     * @param string $namespace
     *
     * @return string|null
     */
    protected function getVendorPathByNamespace(string $namespace): ?string
    {
        if (!$namespace) {
            return null;
        }

        $pathToModules = $this->config->getPathToInternalNamespace($namespace);

        return dirname($pathToModules) . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $module
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    protected function resolveCorePaths($module)
    {
        $paths = [];
        [$namespace, $module] = explode('.', $module, 2);

        if ($module === 'all') {
            $pathToInternalNamespace = $this->config->getPathToInternalNamespace($namespace);
            if ($pathToInternalNamespace === null) {
                throw new RuntimeException('Namespace invalid: ' . $namespace);
            }

            $modules = $this->getCoreModules($pathToInternalNamespace);
            foreach ($modules as $module) {
                $path = $pathToInternalNamespace . $module . DIRECTORY_SEPARATOR;
                $paths = $this->addPath($paths, $path, $namespace);
            }

            return $paths;
        }

        $pathToInternalNamespace = $this->config->getPathToInternalNamespace($namespace);
        if ($pathToInternalNamespace && is_dir($pathToInternalNamespace . $module)) {
            return $this->addPath($paths, $pathToInternalNamespace . $module . DIRECTORY_SEPARATOR, $namespace);
        }

        $vendor = $this->dasherize($namespace);
        $module = $this->dasherize($module);
        $path = $this->config->getPathToRoot() . 'vendor' . DIRECTORY_SEPARATOR . $vendor . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR;
        $paths = $this->addPath($paths, $path, $namespace);

        return $paths;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function dasherize($name)
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
     * @return array
     */
    protected function getCoreModules($path)
    {
        /** @var \Symfony\Component\Finder\SplFileInfo[] $directories */
        $directories = (new Finder())
            ->directories()
            ->in($path)
            ->depth('== 0');

        $modules = [];
        foreach ($directories as $dir) {
            $modules[] = $dir->getFilename();
        }

        return $modules;
    }

    /**
     * @param string $path
     * @param string $fallbackPath
     *
     * @return int
     */
    protected function getDefaultLevel($path, $fallbackPath)
    {
        $configLevel = $this->config->getPhpstanLevel();

        if (file_exists($path . 'phpstan.json')) {
            $configFile = $path . 'phpstan.json';
        } else {
            $directory = dirname($fallbackPath) . DIRECTORY_SEPARATOR;
            $configFile = $directory . 'phpstan.json';
        }

        if (!file_exists($configFile)) {
            return $configLevel;
        }

        $content = file_get_contents($configFile);
        $json = json_decode($content, true);

        return $json[static::DEFAULT_LEVEL];
    }

    /**
     * @param string $buffer
     *
     * @return void
     */
    protected function addErrors(string $buffer): void
    {
        preg_match('#\[ERROR\] Found (\d+) error#i', $buffer, $matches);
        if (!$matches) {
            return;
        }
        $this->errorCount += (int)$matches[1];
    }
}
