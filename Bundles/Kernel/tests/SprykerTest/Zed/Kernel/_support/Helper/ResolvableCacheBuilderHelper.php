<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\Helper;

use Codeception\Module;
use Spryker\Zed\Kernel\Business\KernelBusinessFactory;
use SprykerTest\Shared\Testify\Helper\ClassHelperTrait;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Shared\Testify\Helper\VirtualFilesystemHelperTrait;
use SprykerTest\Zed\Testify\Helper\BusinessHelper;

class ResolvableCacheBuilderHelper extends Module
{
    use VirtualFilesystemHelperTrait;
    use ConfigHelperTrait;
    use ClassHelperTrait;

    protected const CLASS_KEY_STORE = 'store';
    protected const CLASS_KEY_PROJECT = 'project';
    protected const CLASS_KEY_CORE = 'core';

    protected const CACHE_KEY = 'KernelZedFacade';

    protected const CURRENT_STORE = 'DE';
    protected const MODULE_NAME = 'FooBar';

    protected const PATH_TO_CACHE_FILE = 'vfs://root/directory/cacheFile.php';

    /**
     * @var array
     */
    protected $projectOrganizations = ['Pyz'];

    /**
     * @var array
     */
    protected $coreOrganizations = ['Spryker'];

    /**
     * @return void
     */
    protected function arrangeCacheBuilderTest(): void
    {
        $structure = [
            'src' => [
                'Organization' => [
                    'Application' => [
                        $this->getModuleName() => [
                            'Layer' => [
                                $this->getModuleName() . 'Facade.php' => '',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $this->getConfigHelper()->mockSharedConfigMethod('getResolvableCacheFilePath', static::PATH_TO_CACHE_FILE);
        $this->getConfigHelper()->mockSharedConfigMethod('getProjectOrganizations', $this->projectOrganizations);
        $this->getConfigHelper()->mockSharedConfigMethod('getCoreOrganizations', $this->coreOrganizations);
        $this->getConfigHelper()->mockSharedConfigMethod('getCurrentStoreName', static::CURRENT_STORE);

        $virtualDirectory = $this->getVirtualFilesystemHelper()->getVirtualDirectory($structure);
        $path = sprintf('%ssrc/Organization/Application/', $virtualDirectory);

        $this->getConfigHelper()->mockConfigMethod('getPathsToProjectModules', [$path]);
        $this->getConfigHelper()->mockConfigMethod('getPathsToCoreModules', [$path]);
    }

    /**
     * @return string
     */
    public function getStoreClassName(): string
    {
        $moduleNameCandidate = sprintf('%s%s', static::MODULE_NAME, static::CURRENT_STORE);

        return sprintf('\\Pyz\\Zed\\%s\\Business\\%sFacade', $moduleNameCandidate, static::MODULE_NAME);
    }

    /**
     * @return string
     */
    public function getProjectClassName(): string
    {
        return sprintf('\\Pyz\\Zed\\%s\\Business\\%sFacade', static::MODULE_NAME, static::MODULE_NAME);
    }

    /**
     * @return string
     */
    public function getCoreClassName(): string
    {
        return sprintf('\\Spryker\\Zed\\%s\\Business\\%sFacade', static::MODULE_NAME, static::MODULE_NAME);
    }

    /**
     * @return void
     */
    public function arrangeStoreClassCacheBuilderTest(): void
    {
        $this->arrangeCacheBuilderTest();

        $this->getClassHelper()->createAutoloadableClass($this->getStoreClassName());
        $this->getClassHelper()->createAutoloadableClass($this->getProjectClassName());
        $this->getClassHelper()->createAutoloadableClass($this->getCoreClassName());
    }

    /**
     * @return void
     */
    public function arrangeProjectClassCacheBuilderTest(): void
    {
        $this->arrangeCacheBuilderTest();

        $this->getClassHelper()->createAutoloadableClass($this->getProjectClassName());
        $this->getClassHelper()->createAutoloadableClass($this->getCoreClassName());
    }

    /**
     * @return void
     */
    public function arrangeCoreClassCacheBuilderTest(): void
    {
        $this->arrangeCacheBuilderTest();

        $this->getClassHelper()->createAutoloadableClass($this->getCoreClassName());
    }

    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return static::MODULE_NAME;
    }

    /**
     * @param string $resolvableType
     *
     * @return string
     */
    public function getCacheKey(string $resolvableType = 'ZedFacade'): string
    {
        return sprintf('%s%s', static::MODULE_NAME, $resolvableType);
    }

    /**
     * @return array
     */
    public function getExpectedFacadeClassNames(): array
    {
        return $this->buildExpectedClassNames('\\%s\\Zed\\%s\\%sFacade');
    }

    /**
     * @param string $classNamePattern
     *
     * @return array
     */
    protected function buildExpectedClassNames(string $classNamePattern): array
    {
        $classNames = [];

        foreach ($this->buildModuleNameCanditates($this->getModuleName()) as $moduleNameCandidate) {
            $classNames[] = $this->buildClassNameCandidate('Pyz', $this->getModuleName(), $moduleNameCandidate, $classNamePattern);
        }

        $classNames[] = $this->buildClassNameCandidate('Spryker', $this->getModuleName(), $this->getModuleName(), $classNamePattern);

        return $classNames;
    }

    /**
     * @param string $organization
     * @param string $moduleName
     * @param string $moduleNameCandidate
     * @param string $pattern
     *
     * @return string
     */
    protected function buildClassNameCandidate(string $organization, string $moduleName, string $moduleNameCandidate, string $pattern): string
    {
        return sprintf($pattern, $organization, $moduleNameCandidate, $moduleName);
    }

    /**
     * @param string $moduleName
     *
     * @return array
     */
    protected function buildModuleNameCanditates(string $moduleName): array
    {
        return [
            $moduleName . static::CURRENT_STORE,
            $moduleName,
        ];
    }

    /**
     * @return void
     */
    public function assertCacheHasStoreClass(): void
    {
        $this->assertCacheHasExpectedValue($this->getStoreClassName());
    }

    /**
     * @return void
     */
    public function assertCacheHasProjectClass(): void
    {
        $this->assertCacheHasExpectedValue($this->getProjectClassName());
    }

    /**
     * @return void
     */
    public function assertCacheHasCoreClass(): void
    {
        $this->assertCacheHasExpectedValue($this->getCoreClassName());
    }

    /**
     * @param string $expectedCacheValue
     *
     * @return void
     */
    protected function assertCacheHasExpectedValue(string $expectedCacheValue): void
    {
        $this->assertTrue(file_exists(static::PATH_TO_CACHE_FILE), 'Cache file does not exists.');

        $cachedData = $this->getCacheData();

        $cacheKey = $this->getCacheKey();

        $this->assertTrue(count($cachedData) > 0, 'At least one cache entry expected but cache is empty.');
        $this->assertTrue(isset($cachedData[$cacheKey]), sprintf('Cache key "%s" not found. Found cache keys: %s', $cacheKey, implode(', ', array_keys($cachedData))));

        $currentCacheValue = $cachedData[$cacheKey];

        $this->assertSame(
            $expectedCacheValue,
            $currentCacheValue,
            sprintf('Expected "%s" but found "%s" for cache key "%s" given.', $expectedCacheValue, $currentCacheValue, $cacheKey)
        );
    }

    /**
     * @return array
     */
    public function getCacheData(): array
    {
        $fileContent = file_get_contents(static::PATH_TO_CACHE_FILE);

        return include(static::PATH_TO_CACHE_FILE);
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\KernelBusinessFactory
     */
    protected function getBusinessFactory(): KernelBusinessFactory
    {
        /** @var \Spryker\Zed\Kernel\Business\KernelBusinessFactory $businessFactory */
        $businessFactory = $this->getBusinessHelper()->getFactory();

        return $businessFactory;
    }

    /**
     * @return \SprykerTest\Zed\Testify\Helper\BusinessHelper
     */
    protected function getBusinessHelper(): BusinessHelper
    {
        /** @var \SprykerTest\Zed\Testify\Helper\BusinessHelper $businessHelper */
        $businessHelper = $this->getModule('\\' . BusinessHelper::class);

        return $businessHelper;
    }
}
