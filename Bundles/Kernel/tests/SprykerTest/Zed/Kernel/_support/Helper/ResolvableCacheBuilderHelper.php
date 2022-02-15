<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\Helper;

use Codeception\Module;
use Codeception\Stub;
use Spryker\Shared\Kernel\ClassResolver\ModuleNamePostfixProvider\ModuleNamePostfixProvider;
use Spryker\Shared\Kernel\ClassResolver\ModuleNamePostfixProvider\ModuleNamePostfixProviderInterface;
use Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfig;
use Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfigInterface;
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

    /**
     * @var string
     */
    protected const CODE_BUCKET_CB1 = 'CB1';

    /**
     * @var string
     */
    protected const CODE_BUCKET_CB2 = 'CB2';

    /**
     * @var string
     */
    protected const DEFAULT_MODULE_NAME_POSTFIX_VALUE = '';

    /**
     * @var string
     */
    protected const MODULE_NAME = 'FooBar';

    /**
     * @var string
     */
    protected const PATH_TO_CACHE_FILE = 'vfs://root/directory/cacheFile%s.php';

    /**
     * @var array<string>
     */
    protected $projectOrganizations = ['Pyz'];

    /**
     * @var array<string>
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

        $this->getConfigHelper()->mockSharedConfigMethod('getResolvableCacheFilePathPattern', static::PATH_TO_CACHE_FILE);
        $this->getConfigHelper()->mockSharedConfigMethod('getProjectOrganizations', $this->projectOrganizations);
        $this->getConfigHelper()->mockSharedConfigMethod('getCoreOrganizations', $this->coreOrganizations);

        $virtualDirectory = $this->getVirtualFilesystemHelper()->getVirtualDirectory($structure);
        $path = sprintf('%ssrc/Organization/Application/', $virtualDirectory);

        $this->getConfigHelper()->mockConfigMethod('getPathsToProjectModules', [$path]);
        $this->getConfigHelper()->mockConfigMethod('getPathsToCoreModules', [$path]);
    }

    /**
     * @param string $codeBucket
     *
     * @return string
     */
    public function getAutoloadableCodeBucketClassName(string $codeBucket): string
    {
        $moduleNameCandidate = sprintf('%s%s', static::MODULE_NAME, $codeBucket);

        return sprintf('\\Pyz\\Zed\\%s\\Business\\%sFacade', $moduleNameCandidate, static::MODULE_NAME);
    }

    /**
     * @return string
     */
    public function getAutoloadableProjectClassName(): string
    {
        return sprintf('\\Pyz\\Zed\\%s\\Business\\%sFacade', static::MODULE_NAME, static::MODULE_NAME);
    }

    /**
     * @return string
     */
    public function getAutoloadableCoreClassName(): string
    {
        return sprintf('\\Spryker\\Zed\\%s\\Business\\%sFacade', static::MODULE_NAME, static::MODULE_NAME);
    }

    /**
     * @return void
     */
    public function arrangeCodeBucketClassCacheBuilderTest(): void
    {
        $this->arrangeCacheBuilderTest();

        $this->getBusinessHelper()->mockSharedFactoryMethod(
            'createModuleNamePostfixProvider',
            $this->getModuleNamePostfixProviderStub(true),
        );

        $this->getClassHelper()->createAutoloadableClass($this->getAutoloadableCodeBucketClassName(static::CODE_BUCKET_CB1));
        $this->getClassHelper()->createAutoloadableClass($this->getAutoloadableCodeBucketClassName(static::CODE_BUCKET_CB2));
        $this->getClassHelper()->createAutoloadableClass($this->getAutoloadableProjectClassName());
        $this->getClassHelper()->createAutoloadableClass($this->getAutoloadableCoreClassName());
    }

    /**
     * @return void
     */
    public function arrangeProjectClassCacheBuilderTest(): void
    {
        $this->arrangeCacheBuilderTest();

        $this->getClassHelper()->createAutoloadableClass($this->getAutoloadableProjectClassName());
        $this->getClassHelper()->createAutoloadableClass($this->getAutoloadableCoreClassName());
    }

    /**
     * @return void
     */
    public function arrangeCoreClassCacheBuilderTest(): void
    {
        $this->arrangeCacheBuilderTest();

        $this->getClassHelper()->createAutoloadableClass($this->getAutoloadableCoreClassName());
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
     * @param string $cacheFileNamePostfix
     *
     * @return array
     */
    public function getCacheData(string $cacheFileNamePostfix): array
    {
        return include($this->getPathToCacheFile($cacheFileNamePostfix));
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\KernelBusinessFactory
     */
    public function getBusinessFactory(): KernelBusinessFactory
    {
        /** @var \Spryker\Zed\Kernel\Business\KernelBusinessFactory $businessFactory */
        $businessFactory = $this->getBusinessHelper()->getFactory();

        return $businessFactory;
    }

    /**
     * @param string $cacheFileNamePostfix
     *
     * @return string
     */
    public function getPathToCacheFile(string $cacheFileNamePostfix): string
    {
        return sprintf(static::PATH_TO_CACHE_FILE, $cacheFileNamePostfix);
    }

    /**
     * @return array<string>
     */
    public function getCodeBuckets(): array
    {
        return [
            static::CODE_BUCKET_CB1,
            static::CODE_BUCKET_CB2,
        ];
    }

    /**
     * @return string
     */
    public function getDefaultModuleNamePostfixValue(): string
    {
        return static::DEFAULT_MODULE_NAME_POSTFIX_VALUE;
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

    /**
     * @param bool $isApplicationCodeBucketDefined
     *
     * @return \Spryker\Shared\Kernel\ClassResolver\ModuleNamePostfixProvider\ModuleNamePostfixProviderInterface
     */
    protected function getModuleNamePostfixProviderStub(bool $isApplicationCodeBucketDefined = false): ModuleNamePostfixProviderInterface
    {
        $constructorParams = [
            $this->getConfigHelper()->getSharedModuleConfig(),
            $this->getCodeBucketConfigStub(),
        ];

        return Stub::construct(ModuleNamePostfixProvider::class, $constructorParams, [
            'isApplicationCodeBucketDefined' => function () use ($isApplicationCodeBucketDefined) {
                return $isApplicationCodeBucketDefined;
            },
        ]);
    }

    /**
     * @return \Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfigInterface
     */
    protected function getCodeBucketConfigStub(): CodeBucketConfigInterface
    {
        return Stub::make(CodeBucketConfig::class, [
            'getCodeBuckets' => function () {
                return $this->getCodeBuckets();
            },
        ]);
    }
}
