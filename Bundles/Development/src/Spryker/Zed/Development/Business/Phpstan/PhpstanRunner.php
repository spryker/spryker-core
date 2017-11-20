<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Phpstan;

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

    const MEMORY_LIMIT = '512M';
    const CODE_SUCCESS = 0;
    const CODE_ERROR = 0;

    const OPTION_DRY_RUN = 'dry-run';
    const OPTION_MODULE = 'module';

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
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int Exit code
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $module = $input->getOption(static::OPTION_MODULE);

        $message = 'Run PHPMD in PROJECT level';
        if ($module) {
            $message = 'Run PHPMD in module ' . $module;
        }

        $output->writeln($message);

        if ($module) {
            $paths = $this->getPaths($module);
        } else {
            $paths[$this->config->getPathToRoot() . 'src' . DIRECTORY_SEPARATOR] = 'phpstan.neon';
        }

        $result = 0;
        foreach ($paths as $path => $configFilePath) {
            $result |= $this->runCommand($path, $configFilePath, $input, $output);
        }

        return (int)$result;
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
        $command = 'php -d memory_limit=' . static::MEMORY_LIMIT . ' vendor/bin/phpstan analyze --no-progress -c %s %s -l %s';

        $level = $input->getOption('level') ?: $this->config->getPhpstanLevel();
        $command = sprintf($command, $configFilePath, $path, $level);

        if ($input->getOption(static::OPTION_DRY_RUN)) {
            $output->writeln($command);
            return static::CODE_SUCCESS;
        }

        $output->writeln('Checking ' . $path);

        $process = $this->getProcess($command);
        $process->run(function ($type, $buffer) use ($input, $output) {
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

        if (is_dir($path . 'src')) {
            $path .= 'src' . DIRECTORY_SEPARATOR;
        }

        $paths[$path] = $configFilePath ?: 'phpstan.neon';

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
            return $path . 'phpstan.neon';
        }

        return null;
    }

    /**
     * @param string $module
     *
     * @return array
     */
    protected function resolveCorePaths($module)
    {
        $paths = [];
        list ($namespace, $module) = explode('.', $module, 2);

        if ($module === 'core') {
            $modules = $this->getCoreModules($this->config->getPathToCore());
            foreach ($modules as $module) {
                $path = $this->config->getPathToCore() . $module . DIRECTORY_SEPARATOR;
                $paths = $this->addPath($paths, $path);
            }

            return $paths;
        }

        $namespace = $this->normalizeName($namespace);
        if ($namespace === 'spryker' && is_dir($this->config->getPathToCore() . $module)) {
            $paths = $this->addPath($paths, $this->config->getPathToCore() . $module . DIRECTORY_SEPARATOR);

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
            $modules[] = $dir->getFileName();
        }

        return $modules;
    }
}
