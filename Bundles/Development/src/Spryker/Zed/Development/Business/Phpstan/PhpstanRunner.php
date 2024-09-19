<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Phpstan;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;
use RuntimeException;
use SplFileInfo;
use Spryker\Zed\Development\Business\Normalizer\NameNormalizerInterface;
use Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileFinderInterface;
use Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileManagerInterface;
use Spryker\Zed\Development\Business\Traits\PathTrait;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class PhpstanRunner implements PhpstanRunnerInterface
{
    use PathTrait;

    /**
     * @var string
     */
    public const NAMESPACE_SPRYKER = 'Spryker';

    /**
     * @var string
     */
    public const DEFAULT_LEVEL = 'defaultLevel';

    /**
     * @var string
     */
    public const MEMORY_LIMIT = '-1';

    /**
     * @var int
     */
    public const CODE_SUCCESS = 0;

    /**
     * @var string
     */
    public const OPTION_DRY_RUN = 'dry-run';

    /**
     * @var string
     */
    public const OPTION_VERBOSE = 'verbose';

    /**
     * @var string
     */
    public const OPTION_MODULE = 'module';

    /**
     * @var string
     */
    public const OPTION_LEVEL = 'level';

    /**
     * @var string
     */
    public const OPTION_OFFSET = 'offset';

    /**
     * @var string
     */
    public const OPTION_IS_MERGABLE_CONFIG = 'is-mergable-config';

    /**
     * @var int
     */
    protected const SUCCESS_EXIT_CODE = 0;

    /**
     * @var int
     */
    protected const ERROR_EXIT_CODE = 1;

    /**
     * @var string
     */
    protected const PHPSTAN_MEMORY_LIMIT = '4000M';

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
     * @var \Spryker\Zed\Development\Business\Normalizer\NameNormalizerInterface $nameNormalizer
     */
    protected NameNormalizerInterface $nameNormalizer;

    /**
     * @var int
     */
    protected $errorCount = 0;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     * @param \Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileFinderInterface $phpstanConfigFileFinder
     * @param \Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileManagerInterface $phpstanConfigFileManager
     * @param \Spryker\Zed\Development\Business\Normalizer\NameNormalizerInterface $nameNormalizer
     */
    public function __construct(
        DevelopmentConfig $config,
        PhpstanConfigFileFinderInterface $phpstanConfigFileFinder,
        PhpstanConfigFileManagerInterface $phpstanConfigFileManager,
        NameNormalizerInterface $nameNormalizer
    ) {
        $this->config = $config;
        $this->phpstanConfigFileFinder = $phpstanConfigFileFinder;
        $this->phpstanConfigFileManager = $phpstanConfigFileManager;
        $this->nameNormalizer = $nameNormalizer;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int Exit code
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        /** @var string|null $module */
        $module = $input->getOption(static::OPTION_MODULE);

        $message = $this->buildMessage($module);
        $output->writeln($message);

        $paths = $this->getPathsToAnalyze($module, $input);
        $resultCode = 0;
        $count = 0;
        $total = count($paths);
        $this->errorCount = 0;

        asort($paths);

        foreach ($paths as $path => $configFilePath) {
            $count++;
            if ($this->skip($count, $input)) {
                continue;
            }

            $time = time();
            $resultCode |= $this->runCommand($path, $configFilePath, $input, $output);
            $passedTime = time() - $time;

            if ($input->getOption(static::OPTION_VERBOSE)) {
                $output->writeln(sprintf('Finished %s/%s (%s).', $count, $total, $passedTime . 's'));
            }
        }

        if ($this->getErrorCount()) {
            $output->writeln('<error>Total errors found: ' . $this->errorCount . '</error>');
        }

        return $resultCode;
    }

    /**
     * @param string $path
     * @param string $configFilePath
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int Exit code
     */
    protected function runCommand(
        string $path,
        string $configFilePath,
        InputInterface $input,
        OutputInterface $output
    ): int {
        $command = 'php -d memory_limit=%s vendor/bin/phpstan analyze --memory-limit=%s --no-progress -c %s %s -l %s';
        $level = $this->getLevel($input, $path, $configFilePath);

        if (is_dir($path . 'src')) {
            $path .= 'src' . DIRECTORY_SEPARATOR;
        }

        if (!$this->needsRun($path)) {
            if ($output->isVerbose()) {
                $output->writeln(sprintf('Skipping %s (no PHP files found)', $path));
            }

            return static::CODE_SUCCESS;
        }

        $configFilePath .= $this->config->getPhpstanConfigFilename();

        $command = sprintf(
            $command,
            static::MEMORY_LIMIT,
            static::PHPSTAN_MEMORY_LIMIT,
            $configFilePath,
            $path,
            $level,
        );

        if ($input->getOption(static::OPTION_DRY_RUN)) {
            $output->writeln($command);

            return static::CODE_SUCCESS;
        }

        if ($output->isVerbose()) {
            $output->writeln(sprintf('Checking %s (level %s)', $path, $level));
        }

        $commandResult = $this->executeCommand($command, $output, $path);

        if ($this->phpstanConfigFileManager->isMergedConfigFile($configFilePath)) {
            $this->phpstanConfigFileManager->deleteConfigFile($configFilePath);
        }

        return $commandResult;
    }

    /**
     * @param string $command
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $path
     *
     * @return int
     */
    protected function executeCommand(
        string $command,
        OutputInterface $output,
        string $path
    ): int {
        gc_collect_cycles();
        gc_mem_caches();

        $execResult = exec($command, $execOutput);
        $outputBuffer = implode(PHP_EOL, $execOutput);

        if ($execResult !== false) {
            $this->addErrors($outputBuffer);
            preg_match('#\[ERROR\] Found (\d+) error#i', $outputBuffer, $matches);

            if (!$matches) {
                return static::SUCCESS_EXIT_CODE;
            }
        }

        $output->write($outputBuffer);

        if ($output->getVerbosity() === OutputInterface::VERBOSITY_NORMAL) {
            $module = str_replace(getcwd() . DIRECTORY_SEPARATOR, '', $path);
            $output->writeln(sprintf('Errors in module %s', $module));
        }

        return static::ERROR_EXIT_CODE;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param string $path
     * @param string $configFilePath
     *
     * @return int
     */
    protected function getLevel(InputInterface $input, string $path, string $configFilePath): int
    {
        $defaultLevel = $this->getDefaultLevel($path, $configFilePath);
        /** @var string|null $level */
        $level = $input->getOption(static::OPTION_LEVEL);

        if ($level === null) {
            return $defaultLevel;
        }

        if (preg_match('/^([+])(\d)$/', $level, $matches)) {
            return $defaultLevel + (int)$matches[2];
        }

        return (int)$level ?: $defaultLevel;
    }

    /**
     * @param string|bool|null $module
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    protected function getPathsToAnalyze($module, InputInterface $input): array
    {
        if (is_string($module) && $module) {
            $paths = $this->getPaths($module, $input);

            if (!$paths) {
                throw new RuntimeException('No path found for module ' . $module);
            }

            return $paths;
        }

        return [
            $this->config->getPathToRoot() => $this->config->getPathToRoot(),
        ];
    }

    /**
     * @return int
     */
    protected function getErrorCount(): int
    {
        return $this->errorCount;
    }

    /**
     * @param string $module
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return array
     */
    protected function getPaths($module, InputInterface $input)
    {
        if (strpos($module, '.') !== false) {
            $paths = $this->resolveCorePaths($module, $input);
        } else {
            $paths = $this->resolveProjectPaths($module);
        }

        return $paths;
    }

    /**
     * @param string $module
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @throws \RuntimeException
     *
     * @return array<string, string>
     */
    protected function resolveCorePaths($module, InputInterface $input)
    {
        $paths = [];
        [$namespace, $module] = explode('.', $module, 2);

        $pathToInternalNamespace = $this->config->getPathToInternalNamespace($namespace);
        if ($namespace !== null && $pathToInternalNamespace === null) {
            return $this->resolveCommonModulePath([], $module, $namespace, $input);
        }

        if ($module === 'all') {
            if ($pathToInternalNamespace === null) {
                throw new RuntimeException('Namespace invalid: ' . $namespace);
            }

            return $this->resolveCoreModules($paths, $pathToInternalNamespace, $namespace, $input);
        }

        if ($pathToInternalNamespace && is_dir($pathToInternalNamespace . $module)) {
            return $this->addPath($paths, $pathToInternalNamespace . $module . DIRECTORY_SEPARATOR, $namespace, $input);
        }

        return $this->resolveCommonModulePath($paths, $module, $namespace, $input);
    }

    /**
     * @param array $paths
     * @param string $pathToInternalNamespace
     * @param string $namespace
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return array<string>
     */
    protected function resolveCoreModules(array $paths, string $pathToInternalNamespace, string $namespace, InputInterface $input): array
    {
        $modules = $this->getCoreModules($pathToInternalNamespace);
        foreach ($modules as $module) {
            $path = $pathToInternalNamespace . $module . DIRECTORY_SEPARATOR;
            $paths = $this->addPath($paths, $path, $namespace, $input);
        }

        return $paths;
    }

    /**
     * @param array $paths
     * @param string|null $module
     * @param string|null $namespace
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return array<string>
     */
    protected function resolveCommonModulePath(array $paths, ?string $module, ?string $namespace, InputInterface $input): array
    {
        $moduleVendor = $this->nameNormalizer->dasherize($namespace);
        $module = $this->nameNormalizer->dasherize($module);
        $path = sprintf(
            '%s/vendor/%s/%s/',
            $this->config->getPathToRoot(),
            $moduleVendor,
            $module,
        );

        return $this->addPath($paths, $path, $namespace, $input);
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
        $namespaces = array_merge($this->config->getApplicationNamespaces(), $projectNamespaces);
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

                $paths[$layerPath] = $pathToRoot;
            }
        }

        return $paths;
    }

    /**
     * @param array<string, string> $paths
     * @param string $moduleDirectoryPath
     * @param string|null $namespace
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return array<string, string>
     */
    protected function addPath(array $paths, string $moduleDirectoryPath, $namespace, InputInterface $input): array
    {
        $paths[$moduleDirectoryPath] = $this->getConfigFilePathByModuleDirectory($moduleDirectoryPath, $namespace, $input);

        return $paths;
    }

    /**
     * @param string $moduleDirectoryPath
     * @param string|null $namespace
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string
     */
    protected function getConfigFilePathByModuleDirectory(string $moduleDirectoryPath, $namespace, InputInterface $input): string
    {
        $moduleConfigFile = $this->phpstanConfigFileFinder
            ->searchIn($moduleDirectoryPath);

        $vendorDirectoryPath = $this->getVendorPathByNamespace($namespace);

        $vendorConfigFile = $this->phpstanConfigFileFinder
            ->searchIn($vendorDirectoryPath);

        $isMergable = $input->getOption(static::OPTION_IS_MERGABLE_CONFIG);

        if ($moduleConfigFile && $vendorConfigFile && $isMergable === true) {
            return $this->phpstanConfigFileManager->merge(
                [$moduleConfigFile, $vendorConfigFile],
                $this->getConfigFilenameForMerge($moduleConfigFile),
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
                    3,
                ),
            ),
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
     * @param string $path
     *
     * @return array
     */
    protected function getCoreModules($path)
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
     * @param string $path
     * @param string $fallbackPath
     *
     * @throws \RuntimeException
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

        $neonLevel = $this->neonConfigLevel($path);
        if (!file_exists($configFile)) {
            return $neonLevel ?: $configLevel;
        }

        /** @var string $content */
        $content = file_get_contents($configFile);
        $json = json_decode($content, true);
        if (!isset($json[static::DEFAULT_LEVEL])) {
            return $neonLevel ?: $configLevel;
        }

        $definedMinimumLevel = $json[static::DEFAULT_LEVEL];
        if (!$neonLevel && !$definedMinimumLevel) {
            return $configLevel;
        }

        if ($neonLevel && $definedMinimumLevel && $neonLevel !== $definedMinimumLevel) {
            throw new RuntimeException('Can\'t resolve level from both neon and json file, as they differ.');
        }

        return $neonLevel ?: $definedMinimumLevel;
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

    /**
     * Determines 1-based skipping as per `offset[,limit]` config.
     *
     * @param int $count
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return bool
     */
    protected function skip(int $count, InputInterface $input): bool
    {
        $limit = null;
        /** @var string|null $offset */
        $offset = $input->getOption(static::OPTION_OFFSET);
        if ($offset && strpos($offset, ',') !== false) {
            [$offset, $limit] = explode(',', $offset);
        }
        $limit = (int)$limit;
        $offset = (int)$offset;
        if (!$limit && !$offset) {
            return false;
        }

        if ($offset && $count <= $offset) {
            return true;
        }
        if ($limit && $count > ($limit + $offset)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $path
     *
     * @return int|null
     */
    protected function neonConfigLevel(string $path): ?int
    {
        $file = $path . 'phpstan.neon';
        if (!file_exists($file)) {
            return null;
        }

        /** @var string $content */
        $content = file_get_contents($file);
        preg_match('/\blevel:\s*(\d)\b/', $content, $matches);
        if (!$matches) {
            return null;
        }

        return (int)$matches[1] ?: null;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function needsRun(string $path): bool
    {
        $directoryIterator = new RecursiveDirectoryIterator($path);
        $recursiveIterator = new RecursiveIteratorIterator($directoryIterator);
        $regexIterator = new RegexIterator($recursiveIterator, '#\.php$#', RecursiveRegexIterator::GET_MATCH);
        foreach ($regexIterator as $file) {
            return true;
        }

        return false;
    }

    /**
     * @param string|null $module
     *
     * @return string
     */
    protected function buildMessage(?string $module = null): string
    {
        $message = 'Run PHPStan in ';
        if ($this->config->isStandaloneMode()) {
            return $message . 'Standalone Mode';
        }

        if ($module !== null) {
            return $message . 'module ' . $module;
        }

        return $message . 'PROJECT level';
    }
}
