<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\PhpMd;

use ErrorException;
use Spryker\Zed\Development\Business\Normalizer\NameNormalizerInterface;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Process\Process;

class PhpMdRunner
{
    /**
     * @var int
     */
    public const CODE_SUCCESS = 0;

    /**
     * @var string
     */
    protected const CONFIG_LOCAL = 'phpmd.xml';

    /**
     * @var string
     */
    public const BUNDLE_ALL = 'all';

    /**
     * @var string
     */
    public const OPTION_DRY_RUN = 'dry-run';

    /**
     * @var string
     */
    public const OPTION_FORMAT = 'format';

    /**
     * @var string
     */
    protected const OPTION_IGNORE = 'ignore';

    /**
     * @var string
     */
    protected const CUSTOM_RULESET = 'phpmd-ruleset.xml';

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\Development\Business\Normalizer\NameNormalizerInterface $nameNormalizer
     */
    protected NameNormalizerInterface $nameNormalizer;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     * @param \Spryker\Zed\Development\Business\Normalizer\NameNormalizerInterface $nameNormalizer
     */
    public function __construct(
        DevelopmentConfig $config,
        NameNormalizerInterface $nameNormalizer
    ) {
        $this->config = $config;
        $this->nameNormalizer = $nameNormalizer;
    }

    /**
     * @param string|null $bundle
     * @param array<string, mixed> $options
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
            if ($bundle) {
                $message = 'This bundle does not exist';
            }

            throw new ErrorException($message);
        }

        $options += $this->getDefaultIgnoredPath($bundle);

        return $this->runPhpMdCommand($path, $options);
    }

    /**
     * @param string|null $bundle
     *
     * @return array<string, string|null>
     */
    protected function getDefaultIgnoredPath(?string $bundle = null): array
    {
        $dontIgnoreVendor = $bundle !== null || $this->config->isStandaloneMode();

        return [
            static::OPTION_IGNORE => $dontIgnoreVendor ? null : 'vendor/',
        ];
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

            $bundle = $this->nameNormalizer->camelize($bundle);

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
        if ($namespace !== null && $pathToInternalNamespace === null) {
            return $this->resolveCommonModulePath($module, $namespace);
        }

        if ($pathToInternalNamespace !== null && is_dir($pathToInternalNamespace . $module)) {
            return $pathToInternalNamespace . $module . DIRECTORY_SEPARATOR;
        }

        $namespace = $this->nameNormalizer->camelize($namespace);
        $module = $this->nameNormalizer->camelize($module);

        return $this->getFullPathFromRoot($namespace, $module);
    }

    /**
     * @param string $module
     * @param string $namespace
     *
     * @return string
     */
    protected function resolveCommonModulePath(string $module, string $namespace): string
    {
        $moduleVendor = $this->nameNormalizer->dasherize($namespace);
        $module = $this->nameNormalizer->dasherize($module);

        return $this->getFullPathFromRoot($moduleVendor, $module);
    }

    /**
     * @param string $moduleVendor
     * @param string $module
     *
     * @return string
     */
    protected function getFullPathFromRoot(string $moduleVendor, string $module): string
    {
        return sprintf(
            '%s/vendor/%s/%s/',
            $this->config->getPathToRoot(),
            $moduleVendor,
            $module,
        );
    }

    /**
     * @param string $path
     * @param array<string, mixed> $options
     *
     * @return int Exit code
     */
    protected function runPhpMdCommand($path, array $options)
    {
        $pathToFiles = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if (is_dir($pathToFiles . 'src')) {
            $pathToFiles .= 'src' . DIRECTORY_SEPARATOR;
        }

        $format = 'text';
        if ($options[static::OPTION_FORMAT]) {
            $format = $options[static::OPTION_FORMAT];
        }

        $config = $this->resolveRulesetPath($path);

        if ($options['ignore']) {
            $config .= ' --exclude ' . $options['ignore'];
        }

        $command = 'vendor/bin/phpmd ' . $pathToFiles . ' ' . $format . ' ' . $config;
        if (!empty($options[static::OPTION_DRY_RUN])) {
            echo $command . PHP_EOL;

            return static::CODE_SUCCESS;
        }

        $process = new Process(explode(' ', $command), $this->config->getPathToRoot());
        $process->setTimeout($this->config->getProcessTimeout());
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        return $process->getExitCode();
    }

    /**
     * @param string $directory
     *
     * @return string
     */
    protected function resolveRulesetPath(string $directory): string
    {
        $rulesetFilepath = $directory . static::CUSTOM_RULESET;

        if (file_exists($rulesetFilepath) === true) {
            return $rulesetFilepath;
        }

        return $this->getArchitectureStandard($rulesetFilepath);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getArchitectureStandard(string $path): string
    {
        $standardConfig = $this->config->getArchitectureStandard();
        if (!$this->config->isStandaloneMode()) {
            return $standardConfig;
        }

        $configPath = $path . static::CONFIG_LOCAL;
        if (file_exists($configPath)) {
            return $configPath;
        }

        return $standardConfig;
    }
}
