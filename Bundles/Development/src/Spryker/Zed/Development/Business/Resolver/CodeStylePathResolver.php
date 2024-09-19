<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Resolver;

use RuntimeException;
use Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationLoaderInterface;
use Spryker\Zed\Development\Business\Exception\CodeStyleSniffer\PathDoesNotExistException;
use Spryker\Zed\Development\Business\Normalizer\NameNormalizerInterface;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder;

class CodeStylePathResolver implements PathResolverInterface
{
    /**
     * @var string
     */
    protected const ERROR_NAMESPACE_INVALID = 'Namespace is invalid: %s';

    /**
     * @var string
     */
    protected const ERROR_NO_VALID_PATH = 'Could not find a valid path to your module "%s". Expected path "%s". Maybe there is a typo in the module name?';

    /**
     * @var string
     */
    protected const ERROR_SUFFIX_ISNT_POSSIBLE = 'Path suffix option is not possible for "all".';

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected DevelopmentConfig $config;

    /**
     * @var \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationLoaderInterface
     */
    protected $codeStyleSnifferConfigurationLoader;

    /**
     * @var \Spryker\Zed\Development\Business\Normalizer\NameNormalizerInterface $nameNormalizer
     */
    protected NameNormalizerInterface $nameNormalizer;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     * @param \Spryker\Zed\Development\Business\Normalizer\NameNormalizerInterface $nameNormalizer
     * @param \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationLoaderInterface $codeStyleSnifferConfigurationLoader
     */
    public function __construct(
        DevelopmentConfig $config,
        NameNormalizerInterface $nameNormalizer,
        CodeStyleSnifferConfigurationLoaderInterface $codeStyleSnifferConfigurationLoader
    ) {
        $this->config = $config;
        $this->nameNormalizer = $nameNormalizer;
        $this->codeStyleSnifferConfigurationLoader = $codeStyleSnifferConfigurationLoader;
    }

    /**
     * @param string|null $module
     * @param string|null $namespace
     * @param string|null $path
     * @param array<string, mixed> $options
     *
     * @return array<string, \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface>
     */
    public function resolvePaths(?string $module, ?string $namespace, ?string $path, array $options): array
    {
        $path = $path !== null ? trim($path, DIRECTORY_SEPARATOR) : null;

        if ($namespace !== null && $this->config->getPathToInternalNamespace($namespace) === null) {
            return $this->resolveCommonModulePath($module, $namespace, $path, $options);
        }

        if ($namespace) {
            return $this->resolveCorePath($module, $namespace, $path, $options);
        }

        if (!$module) {
            return $this->addPath([], $this->config->getPathToRoot() . $path, $options);
        }

        return $this->resolveProjectPath($module, $path, $options);
    }

    /**
     * @param string|null $module
     * @param string|null $namespace
     * @param string|null $path
     * @param array $options
     *
     * @return array<string, \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface>
     */
    protected function resolveCommonModulePath(?string $module, ?string $namespace, ?string $path, array $options): array
    {
        $moduleVendor = $this->nameNormalizer->dasherize($namespace);
        $module = $this->nameNormalizer->dasherize($module);

        $path = sprintf(
            '%s/%s/%s/',
            APPLICATION_VENDOR_DIR,
            $moduleVendor,
            $module,
        );

        return $this->addPath([], $path, $options);
    }

    /**
     * @param string $module
     * @param string $namespace
     * @param string|null $path
     * @param array<string, mixed> $options
     *
     * @return array<string, \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface>
     */
    protected function resolveCorePath(string $module, string $namespace, ?string $path, array $options): array
    {
        if ($module === 'all') {
            return $this->getPathsToAllCoreModules($namespace, $path, $options);
        }

        return $this->getPathToCoreModule($module, $namespace, $path, $options);
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
        $namespaces = array_merge($this->config->getApplicationNamespaces(), $this->config->getProjectNamespaces());
        $pathToRoot = $this->config->getPathToRoot();

        $paths = [];
        foreach ($namespaces as $namespace) {
            $path = $pathToRoot . 'src' . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR;

            foreach ($this->config->getApplicationLayers() as $layer) {
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
            throw new RuntimeException(static::ERROR_SUFFIX_ISNT_POSSIBLE);
        }

        $pathToInternalNamespace = $this->config->getPathToInternalNamespace($namespace);

        if (!$pathToInternalNamespace) {
            throw new RuntimeException(sprintf(static::ERROR_NAMESPACE_INVALID, $namespace));
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
            static::ERROR_NO_VALID_PATH,
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
    protected function getCorePath($module, $namespace, $pathSuffix = null): string
    {
        $pathToInternalNamespace = $this->config->getPathToInternalNamespace($namespace);
        if ($pathToInternalNamespace && is_dir($pathToInternalNamespace . $module)) {
            return $this->buildPath($pathToInternalNamespace . $module . DIRECTORY_SEPARATOR, $pathSuffix);
        }

        $moduleVendor = $this->nameNormalizer->dasherize($namespace);
        $module = $this->nameNormalizer->dasherize($module);
        $path = sprintf(
            '%s/vendor/%s/%s/',
            $this->config->getPathToRoot(),
            $moduleVendor,
            $module,
        );

        return $this->buildPath($path, $pathSuffix);
    }

    /**
     * @param string $path
     * @param string|null $suffix
     *
     * @return string
     */
    protected function buildPath(string $path, ?string $suffix = null): string
    {
        if (!$suffix) {
            return $path;
        }

        return $path . $suffix;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function isPathValid(string $path): bool
    {
        return (is_file($path) || is_dir($path));
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
}
