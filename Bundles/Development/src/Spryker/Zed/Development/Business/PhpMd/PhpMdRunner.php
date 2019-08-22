<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\PhpMd;

use ErrorException;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Process\Process;
use Zend\Filter\Word\UnderscoreToCamelCase;

class PhpMdRunner
{
    public const CODE_SUCCESS = 0;

    public const BUNDLE_ALL = 'all';

    public const OPTION_DRY_RUN = 'dry-run';
    public const OPTION_FORMAT = 'format';

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
     * @param string $value
     *
     * @return string
     */
    protected function convertToCamelCase(string $value): string
    {
        $filter = new UnderscoreToCamelCase();

        return ucfirst($filter->filter($value));
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
                return $this->config->getPathToCore();
            }

            $bundle = $this->convertToCamelCase($bundle);

            return $this->getPathToBundle($bundle);
        }

        return $this->config->getPathToRoot();
    }

    /**
     * @param string $bundle
     *
     * @return string
     */
    protected function getPathToBundle($bundle)
    {
        if (strpos($bundle, '.') !== false) {
            return $this->resolveCorePaths($bundle);
        }

        return $this->config->getPathToCore() . $bundle . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $module
     *
     * @return string
     */
    protected function resolveCorePaths(string $module): string
    {
        [$namespace, $module] = explode('.', $module, 2);

        $pathToInternalNamespace = $this->config->getPathToInternalNamespace($namespace);
        if ($pathToInternalNamespace !== null && is_dir($pathToInternalNamespace . $module)) {
            return $pathToInternalNamespace . $module . DIRECTORY_SEPARATOR;
        }

        $namespace = $this->convertToCamelCase($namespace);
        $module = $this->convertToCamelCase($module);
        $path = $this->config->getPathToRoot() . 'vendor' . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR;

        return $path;
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

        $config = $this->config->getArchitectureStandard();

        if ($options['ignore']) {
            $config .= ' --exclude ' . $options['ignore'];
        }

        $command = 'vendor/bin/phpmd ' . $pathToFiles . ' ' . $format . ' ' . $config;
        if (!empty($options[static::OPTION_DRY_RUN])) {
            echo $command . PHP_EOL;

            return static::CODE_SUCCESS;
        }

        $process = new Process(explode(' ', $command), $this->config->getPathToRoot(), null, null, 4800);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        return $process->getExitCode();
    }
}
