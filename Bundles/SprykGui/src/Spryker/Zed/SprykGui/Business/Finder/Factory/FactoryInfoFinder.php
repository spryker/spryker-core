<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Finder\Factory;

use Generated\Shared\Transfer\ClassInformationTransfer;
use Generated\Shared\Transfer\MethodInformationTransfer;
use Generated\Shared\Transfer\ReturnTypeTransfer;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionMethod;
use Roave\BetterReflection\Reflector\Exception\IdentifierNotFound;

class FactoryInfoFinder implements FactoryInfoFinderInterface
{
    /**
     * @var string[]
     */
    protected $methodsToFilter = [
        'provideExternalDependencies',
        'injectExternalDependencies',
        'setConfig',
        'resolveBundleConfig',
        'setContainer',
        'getProvidedDependency',
        'resolveDependencyProvider',
        'createDependencyProviderResolver',
        'createContainer',
        'createContainerGlobals',
        'resolveDependencyInjectorCollection',
        'createDependencyInjectorResolver',
        'overwriteForTesting',
        'setQueryContainer',
        'resolveQueryContainer',
        'getQueryContainerResolver',
        'getQueryContainer',
        'setRepository',
        'resolveRepository',
        'getRepositoryResolver',
        'getRepositoryResolver',
        'resolveEntityManager',
        'setEntityManager',
        'getEntityManagerResolver',
        'createContainerWithProvidedDependencies',
        'createDependencyInjector',
    ];

    /**
     * @param string $className
     *
     * @return \Generated\Shared\Transfer\ClassInformationTransfer
     */
    public function findFactoryInformation(string $className): ClassInformationTransfer
    {
        $classInformationTransfer = new ClassInformationTransfer();
        if (!$this->canReflect($className)) {
            return $classInformationTransfer;
        }

        $reflectedClass = $this->getReflectedClass($className);

        if (!($reflectedClass instanceof  ReflectionClass)) {
            return $classInformationTransfer;
        }

        $classInformationTransfer->setFullyQualifiedClassName($className);

        foreach ($reflectedClass->getMethods() as $method) {
            if ($this->shouldIgnore($method)) {
                continue;
            }

            $methodInformationTransfer = new MethodInformationTransfer();
            $methodInformationTransfer
                ->setName($method->getName())
                ->setReturnType($this->getReturnType($method));

            $classInformationTransfer->addMethod($methodInformationTransfer);
        }

        return $classInformationTransfer;
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function canReflect(string $className): bool
    {
        try {
            $betterReflection = new BetterReflection();
            $betterReflection->classReflector()->reflect($className);

            return true;
        } catch (IdentifierNotFound $exception) {
            return false;
        }
    }

    /**
     * @param \Roave\BetterReflection\Reflection\ReflectionMethod $method
     *
     * @return bool
     */
    protected function shouldIgnore(ReflectionMethod $method)
    {
        if (in_array($method->getName(), $this->methodsToFilter)) {
            return true;
        }

        return false;
    }

    /**
     * @param \Roave\BetterReflection\Reflection\ReflectionMethod $method
     *
     * @return \Generated\Shared\Transfer\ReturnTypeTransfer
     */
    protected function getReturnType(ReflectionMethod $method): ReturnTypeTransfer
    {
        $returnTypeTransfer = new ReturnTypeTransfer();
        $returnTypeTransfer->setIsPhpSeven($method->hasReturnType());

        if ($method->hasReturnType()) {
            $returnTypeTransfer->setType($method->getReturnType());

            return $returnTypeTransfer;
        }

        $returnTypes = $method->getDocBlockReturnTypes();
        $returnStrings = [];
        foreach ($returnTypes as $returnType) {
            $returnStrings[] = $returnType->__toString();
        }

        $returnTypeTransfer->setType(implode('|', $returnStrings));

        return $returnTypeTransfer;
    }

    /**
     * @param string $className
     *
     * @return \Roave\BetterReflection\Reflection\Reflection|\Roave\BetterReflection\Reflection\ReflectionClass
     */
    protected function getReflectedClass(string $className)
    {
        $betterReflection = new BetterReflection();
        $reflectedClass = $betterReflection->classReflector()->reflect($className);

        return $reflectedClass;
    }
}
