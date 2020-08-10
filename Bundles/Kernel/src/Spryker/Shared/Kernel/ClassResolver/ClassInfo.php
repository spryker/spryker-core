<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

use RuntimeException;

class ClassInfo
{
    public const TEST_NAMESPACE_SUFFIX = 'Test';
    public const KEY_NAMESPACE = 0;
    public const KEY_APPLICATION = 1;
    public const KEY_BUNDLE = 2;
    public const KEY_LAYER = 3;

    /**
     * @var string|null
     */
    private $callerClassName;

    /**
     * @var string|null
     */
    private $cacheKey;

    /**
     * @var string[]
     */
    protected $callerClassParts = [];

    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\ModuleNameResolver|null
     */
    protected $moduleNameResolver;

    /**
     * @param object|string $callerClass
     *
     * @return $this
     */
    public function setClass($callerClass)
    {
        if (is_object($callerClass)) {
            $callerClass = get_class($callerClass);
        }
        $this->callerClassName = $callerClass;

        $callerClassParts = [
            self::KEY_BUNDLE => $callerClass,
        ];

        if ($this->isFullyQualifiedClassName($callerClass)) {
            $callerClassParts = explode('\\', ltrim($callerClass, '\\'));
            if ($this->shouldTestNamespaceBeAdjusted()) {
                $callerClassParts = $this->adjustTestNamespace($callerClassParts);
            }
        }

        $this->callerClassParts = $callerClassParts;

        $this->callerClassParts[static::KEY_BUNDLE] = $this->getModuleNameResolver()->resolve($this->callerClassParts[static::KEY_BUNDLE]);

        return $this;
    }

    /**
     * @param string $resolvableType
     *
     * @return string
     */
    public function getCacheKey(string $resolvableType): string
    {
        if (!$this->cacheKey) {
            $this->cacheKey = $this->buildCacheKey($resolvableType);
        }

        return $this->cacheKey;
    }

    /**
     * @param string $resolvableType
     *
     * @return string
     */
    protected function buildCacheKey(string $resolvableType): string
    {
        $module = $this->getModule();
        $layer = $this->getLayer();

        $cacheKey = $module . $resolvableType;

        if ($resolvableType === 'ZedFactory') {
            $cacheKey = $module . $resolvableType . $layer;
        }

        return $cacheKey;
    }

    /**
     * @return bool
     */
    protected function shouldTestNamespaceBeAdjusted(): bool
    {
        return APPLICATION_ENV === 'devtest';
    }

    /**
     * @param string $callerClass
     *
     * @return bool
     */
    private function isFullyQualifiedClassName($callerClass)
    {
        return (strpos($callerClass, '\\') !== false);
    }

    /**
     * @return string|null
     */
    public function getCallerClassName()
    {
        return $this->callerClassName;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->callerClassParts[static::KEY_NAMESPACE];
    }

    /**
     * @return string
     */
    public function getApplication()
    {
        return $this->callerClassParts[static::KEY_APPLICATION];
    }

    /**
     * @throws \RuntimeException
     *
     * @return string
     */
    public function getModule(): string
    {
        if (!isset($this->callerClassParts[static::KEY_BUNDLE])) {
            throw new RuntimeException('Could not extract a module name which is mandatory for the resolver to work!');
        }

        return $this->callerClassParts[static::KEY_BUNDLE];
    }

    /**
     * @return string
     */
    public function getLayer(): string
    {
        return $this->callerClassParts[static::KEY_LAYER] ?? '';
    }

    /**
     * @deprecated Use {@link \Spryker\Shared\Kernel\ClassResolver\ClassInfo::getModule()} instead.
     *
     * @return string
     */
    public function getBundle()
    {
        $bundleName = $this->callerClassParts[static::KEY_BUNDLE];
        $bundleName = $this->getModuleNameResolver()->resolve($bundleName);

        return $bundleName;
    }

    /**
     * @return \Spryker\Shared\Kernel\ClassResolver\ModuleNameResolver
     */
    protected function getModuleNameResolver()
    {
        if (!$this->moduleNameResolver) {
            $this->moduleNameResolver = new ModuleNameResolver();
        }

        return $this->moduleNameResolver;
    }

    /**
     * @param array $callerClassParts
     *
     * @return array
     */
    protected function adjustTestNamespace(array $callerClassParts)
    {
        // Support obsolete test namespace convention: Unit\PyzTest\Zed\..
        if ($this->isTestNamespace($callerClassParts[static::KEY_APPLICATION], static::TEST_NAMESPACE_SUFFIX)) {
            array_shift($callerClassParts);
        }

        if ($this->isTestNamespace($callerClassParts[static::KEY_NAMESPACE], static::TEST_NAMESPACE_SUFFIX)) {
            $callerClassParts = $this->removeTestNamespaceSuffix($callerClassParts, static::TEST_NAMESPACE_SUFFIX);
        }

        return $callerClassParts;
    }

    /**
     * @param string $rootNamespace
     * @param string $testNamespaceSuffix
     *
     * @return bool
     */
    protected function isTestNamespace($rootNamespace, $testNamespaceSuffix)
    {
        $rootNamespaceLength = strlen($rootNamespace);
        $testNamespaceSuffixLength = strlen($testNamespaceSuffix);

        if ($testNamespaceSuffixLength >= $rootNamespaceLength) {
            return false;
        }

        return substr_compare($rootNamespace, $testNamespaceSuffix, $rootNamespaceLength - $testNamespaceSuffixLength, $testNamespaceSuffixLength) === 0;
    }

    /**
     * @param array $callerClassParts
     * @param string $testNamespaceSuffix
     *
     * @return array
     */
    protected function removeTestNamespaceSuffix(array $callerClassParts, $testNamespaceSuffix)
    {
        $namespace = $callerClassParts[static::KEY_NAMESPACE];
        $namespaceWithoutTestSuffix = substr($namespace, 0, -strlen($testNamespaceSuffix));
        $callerClassParts[static::KEY_NAMESPACE] = $namespaceWithoutTestSuffix;

        return $callerClassParts;
    }
}
