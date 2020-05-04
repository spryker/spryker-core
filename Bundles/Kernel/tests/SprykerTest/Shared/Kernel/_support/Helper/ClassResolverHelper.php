<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel\Helper;

use Codeception\Module;
use Codeception\Stub;
use Exception;
use ReflectionClass;
use Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver;
use Spryker\Shared\Kernel\ClassResolver\ClassNameCandidatesBuilder\ClassNameCandidatesBuilder;
use Spryker\Shared\Kernel\ClassResolver\ClassNameFinder\ClassNameFinder;
use Spryker\Shared\Kernel\ClassResolver\ModuleNameCandidatesBuilder\ModuleNameCandidatesBuilder;
use Spryker\Shared\Kernel\ClassResolver\ResolverCacheManager;
use Spryker\Shared\Kernel\KernelConfig;
use SprykerTest\Shared\Testify\Helper\ClassHelperTrait;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;

class ClassResolverHelper extends Module
{
    use ConfigHelperTrait;
    use ClassHelperTrait;

    public const MODULE_NAME = 'ModuleName';

    protected const PROJECT_ORGANIZATION = 'ProjectOrganization';
    protected const CORE_ORGANIZATION = 'CoreOrganization';
    protected const STORE_NAME = 'STORE';

    /**
     * @param string $resolverClassName
     *
     * @return \Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver
     */
    public function getResolver(string $resolverClassName): AbstractClassResolver
    {
        /** @var \Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver $resolverStub */
        $resolverStub = Stub::make($resolverClassName, [
            'getClassNameFinder' => function () use ($resolverClassName) {
                $sharedConfig = $this->getConfigStub($resolverClassName);
                $moduleNameCandidatesBuilder = new ModuleNameCandidatesBuilder($sharedConfig);
                $classNameCandidatesBuilder = new ClassNameCandidatesBuilder($moduleNameCandidatesBuilder, $sharedConfig);
                $resolverCacheManager = new ResolverCacheManager();

                return new ClassNameFinder($classNameCandidatesBuilder, $resolverCacheManager);
            },
        ]);

        return $resolverStub;
    }

    /**
     * @param string $resolverClassName
     *
     * @return \Spryker\Shared\Kernel\KernelConfig
     */
    protected function getConfigStub(string $resolverClassName): KernelConfig
    {
        $resolvableType = $this->getResolvableType($resolverClassName);
        $classNamePattern = $this->getClassNamePattern($resolverClassName);

        $resolvableTypeClassNamePatternMap = [
            $resolvableType => $classNamePattern,
        ];

        /** @var \Spryker\Shared\Kernel\KernelConfig $configStub */
        $configStub = Stub::make(KernelConfig::class, [
            'getProjectOrganizations' => function () {
                return ['ProjectOrganization'];
            },
            'getCoreOrganizations' => function () {
                return ['CoreOrganization'];
            },
            'getResolvableTypeClassNamePatternMap' => function () use ($resolvableTypeClassNamePatternMap) {
                return $resolvableTypeClassNamePatternMap;
            },
            'getCurrentStoreName' => function () {
                return static::STORE_NAME;
            },
        ]);

        return $configStub;
    }

    /**
     * @param string $resolverClassName
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getResolvableType(string $resolverClassName): string
    {
        $reflection = new ReflectionClass($resolverClassName);

        $resolvableType = $reflection->getConstant('RESOLVABLE_TYPE');

        if ($resolvableType === null) {
            throw new Exception(sprintf('"%s::RESOLVABLE_TYPE" seems to be not set.', $resolverClassName));
        }

        return $resolvableType;
    }

    /**
     * @param string $resolverClassName
     *
     * @return string
     */
    public function getProjectStoreClassName(string $resolverClassName): string
    {
        $classNamePattern = $this->getClassNamePattern($resolverClassName);

        return $this->buildClassName($classNamePattern, static::PROJECT_ORGANIZATION, static::MODULE_NAME, static::STORE_NAME);
    }

    /**
     * @param string $resolverClassName
     *
     * @return string
     */
    public function getProjectClassName(string $resolverClassName): string
    {
        $classNamePattern = $this->getClassNamePattern($resolverClassName);

        return $this->buildClassName($classNamePattern, static::PROJECT_ORGANIZATION, static::MODULE_NAME);
    }

    /**
     * @param string $resolverClassName
     *
     * @return string
     */
    public function getCoreClassName(string $resolverClassName): string
    {
        $classNamePattern = $this->getClassNamePattern($resolverClassName);

        return $this->buildClassName($classNamePattern, static::CORE_ORGANIZATION, static::MODULE_NAME);
    }

    /**
     * @param string $resolverClassName
     *
     * @return string
     */
    protected function getClassNamePattern(string $resolverClassName): string
    {
        $config = $this->getConfig();

        return $config->getResolvableTypeClassNamePatternMap()[$this->getResolvableType($resolverClassName)];
    }

    /**
     * @return \Spryker\Shared\Kernel\KernelConfig
     */
    protected function getConfig(): KernelConfig
    {
        /** @var \Spryker\Shared\Kernel\KernelConfig $config */
        $config = $this->getConfigHelper()->getSharedModuleConfig('Kernel');

        return $config;
    }

    /**
     * @param string $classNamePattern
     * @param string $organization
     * @param string $moduleName
     * @param string $storeName
     *
     * @return string
     */
    protected function buildClassName(string $classNamePattern, string $organization, string $moduleName, string $storeName = ''): string
    {
        $moduleNameCandidate = $moduleName . $storeName;

        return ltrim(sprintf($classNamePattern, $organization, $moduleNameCandidate, $moduleName), '\\');
    }

    /**
     * @param string $resolverClassName
     *
     * @return void
     */
    public function createProjectStoreClass(string $resolverClassName): void
    {
        $className = $this->getProjectStoreClassName($resolverClassName);
        $extends = $this->getClassToExtend($resolverClassName);

        $this->getClassHelper()->createAutoloadableClass($className, $extends);
    }

    /**
     * @param string $resolverClassName
     *
     * @return void
     */
    public function createProjectClass(string $resolverClassName): void
    {
        $className = $this->getProjectClassName($resolverClassName);
        $extends = $this->getClassToExtend($resolverClassName);

        $this->getClassHelper()->createAutoloadableClass($className, $extends);
    }

    /**
     * @param string $resolverClassName
     *
     * @return void
     */
    public function createCoreClass(string $resolverClassName): void
    {
        $className = $this->getCoreClassName($resolverClassName);
        $extends = $this->getClassToExtend($resolverClassName);

        $this->getClassHelper()->createAutoloadableClass($className, $extends);
    }

    /**
     * @param string $resolverClassName
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getClassToExtend(string $resolverClassName): string
    {
        $reflection = new ReflectionClass($resolverClassName);
        $reflectionMethod = $reflection->getMethod('resolve');

        $returnType = $reflectionMethod->getReturnType();

        if ($returnType !== null) {
            return '\\' . $reflectionMethod->getReturnType();
        }

        $docComment = $reflectionMethod->getDocComment();

        if (preg_match('/@return\s(.*)/', $docComment, $match)) {
            return $match[1];
        }

        throw new Exception(sprintf('Could not extract return type from "%s::resolve()".', $resolverClassName));
    }
}
