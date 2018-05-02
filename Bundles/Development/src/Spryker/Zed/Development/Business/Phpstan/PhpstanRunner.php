<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Phpstan;

use RuntimeException;
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

    const NAMESPACE_SPRYKER_SHOP = 'SprykerShop';
    const NAMESPACE_SPRYKER = 'Spryker';

    const DEFAULT_LEVEL = 'defaultLevel';
    const MEMORY_LIMIT = '512M';
    const CODE_SUCCESS = 0;
    const CODE_ERROR = 0;

    const OPTION_DRY_RUN = 'dry-run';
    const OPTION_VERBOSE = 'verbose';
    const OPTION_MODULE = 'module';

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @var int
     */
    protected $errors = 0;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(DevelopmentConfig $config)
    {
        $this->config = $config;
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
        $this->errors = 0;
        foreach ($paths as $path => $configFilePath) {
            $resultCode |= $this->runCommand($path, $configFilePath, $input, $output);
            $count++;
            if ($input->getOption(static::OPTION_VERBOSE)) {
                $output->writeln(sprintf('Finished %s/%s.', $count, $total));
            }
        }
        if ($this->errors) {
            $output->writeln('<error>Total errors found: ' . $this->errors . '</error>');
        }

        return (int)$resultCode;
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

        $level = $input->getOption('level') ?: $this->getDefaultLevel($path, $configFilePath);

        if (is_dir($path . 'src')) {
            $path .= 'src' . DIRECTORY_SEPARATOR;
        }
        $configFilePath .= 'phpstan.neon';

        $command = sprintf($command, static::MEMORY_LIMIT, $configFilePath, $path, $level);

        if ($input->getOption(static::OPTION_DRY_RUN)) {
            $output->writeln($command);
            return static::CODE_SUCCESS;
        }

        $output->writeln(sprintf('Checking %s (level %s)', $path, $level));

        $process = $this->getProcess($command);
        $process->run(function ($type, $buffer) use ($output) {
            $this->addErrors($buffer);

            $output->write($buffer);
        });

        return $process->run();
    }

    /**
     * @param string $command
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess($command)
    {
        return new Process($command, null, null, null, 0);
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

            foreach (DevelopmentConfig::APPLICATION_LAYERS as $layer) {
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
     * @param array $paths
     * @param string $path
     * @param string|null $configFilePath
     *
     * @return array
     */
    protected function addPath(array $paths, $path, $configFilePath = null)
    {
        if (!$configFilePath) {
            $configFilePath = $this->detectConfigFilePath($path);
        }

        $paths[$path] = $configFilePath ?: $this->config->getPathToRoot();

        return $paths;
    }

    /**
     * @param string $path
     *
     * @return string|null
     */
    protected function detectConfigFilePath($path)
    {
        if (file_exists($path . 'phpstan.neon')) {
            return $path;
        }

        return null;
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
        list ($namespace, $module) = explode('.', $module, 2);

        if ($module === 'all') {
            if ($namespace === static::NAMESPACE_SPRYKER_SHOP) {
                $corePath = $this->config->getPathToShop();
            } elseif ($namespace === static::NAMESPACE_SPRYKER) {
                $corePath = $this->config->getPathToCore();
            } else {
                throw new RuntimeException('Namespace invalid: ' . $namespace);
            }

            $modules = $this->getCoreModules($corePath);
            foreach ($modules as $module) {
                $path = $corePath . $module . DIRECTORY_SEPARATOR;
                $paths = $this->addPath($paths, $path);
            }

            return $paths;
        }

        $namespace = $this->normalizeName($namespace);
        if ($namespace === $this->normalizeName(static::NAMESPACE_SPRYKER) && is_dir($this->config->getPathToCore() . $module)) {
            $paths = $this->addPath($paths, $this->config->getPathToCore() . $module . DIRECTORY_SEPARATOR);

            return $paths;
        }

        if ($namespace === $this->normalizeName(static::NAMESPACE_SPRYKER_SHOP) && is_dir($this->config->getPathToShop() . $module)) {
            $paths = $this->addPath($paths, $this->config->getPathToShop() . $module . DIRECTORY_SEPARATOR);

            return $paths;
        }

        $module = $this->normalizeName($module);
        $path = $this->config->getPathToRoot() . 'vendor' . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . $module;
        $paths = $this->addPath($paths, $path);

        return $paths;
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
    protected function addErrors($buffer)
    {
        preg_match('#\[ERROR\] Found (\d+) error#i', $buffer, $matches);
        if (!$matches) {
            return;
        }
        $this->errors += (int)$matches[1];
    }
}
