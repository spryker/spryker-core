<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\ClassResolver\DependencyInjector;

use Spryker\Glue\Kernel\ClassResolver\AbstractClassResolver;
use Spryker\Glue\Kernel\Dependency\Injector\DependencyInjectorCollection;
use Spryker\Glue\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface;
use Spryker\Glue\Kernel\Dependency\Injector\DependencyInjectorInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;

class DependencyInjectorResolver extends AbstractClassResolver
{
    protected const CLASS_NAME_PATTERN = '\\%1$s\\Glue\\%2$s%3$s\\Dependency\\Injector\\%4$sDependencyInjector';
    protected const KEY_FROM_MODULE = '%fromModule%';

    /**
     * @var string
     */
    private $fromModule;

    /**
     * @param object|string $callerClass
     *
     * @return \Spryker\Glue\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface
     */
    public function resolve($callerClass): DependencyInjectorCollectionInterface
    {
        $dependencyInjectorCollection = $this->getDependencyInjectorCollection();

        $this->setCallerClass($callerClass);
        $injectToModules = $this->getClassInfo()->getBundle();
        $injectFromModules = $this->getInjectorModules($injectToModules);

        foreach ($injectFromModules as $injectFromModule) {
            $this->fromModule = $injectFromModule;

            $this->unsetCurrentCacheEntry();

            if ($this->canResolve()) {
                $resolvedInjector = $this->getResolvedClassInstance();
                $dependencyInjectorCollection->addDependencyInjector($resolvedInjector);
            }
        }

        return $dependencyInjectorCollection;
    }

    /**
     * @return \Spryker\Glue\Kernel\Dependency\Injector\DependencyInjectorInterface
     */
    protected function getResolvedClassInstance(): DependencyInjectorInterface
    {
        /** @var \Spryker\Glue\Kernel\Dependency\Injector\DependencyInjectorInterface $dependencyInjector */
        $dependencyInjector = parent::getResolvedClassInstance();

        return $dependencyInjector;
    }

    /**
     * @return string
     */
    public function getClassPattern(): string
    {
        return sprintf(
            static::CLASS_NAME_PATTERN,
            static::KEY_NAMESPACE,
            static::KEY_FROM_MODULE,
            static::KEY_STORE,
            static::KEY_BUNDLE
        );
    }

    /**
     * @param string $namespace
     * @param string|null $store
     *
     * @return string
     */
    protected function buildClassName($namespace, $store = null): string
    {
        $searchAndReplace = [
            static::KEY_NAMESPACE => $namespace,
            static::KEY_BUNDLE => $this->getClassInfo()->getBundle(),
            static::KEY_FROM_MODULE => $this->fromModule,
            static::KEY_STORE => $store,
        ];

        $className = str_replace(
            array_keys($searchAndReplace),
            array_values($searchAndReplace),
            $this->getClassPattern()
        );

        return $className;
    }

    /**
     * @param string $injectToModules
     *
     * @return array
     */
    protected function getInjectorModules(string $injectToModules): array
    {
        $injectorConfiguration = $this->getDependencyInjectorConfiguration();
        if (!isset($injectorConfiguration[$injectToModules])) {
            return [];
        }

        return $injectorConfiguration[$injectToModules];
    }

    /**
     * @return array
     */
    protected function getDependencyInjectorConfiguration(): array
    {
        return Config::get(
            KernelConstants::DEPENDENCY_INJECTOR_GLUE,
            $this->getDefaultGlueDependencyInjectorConfiguration()
        );
    }

    /**
     * @return \Spryker\Glue\Kernel\Dependency\Injector\DependencyInjectorCollection
     */
    protected function getDependencyInjectorCollection(): DependencyInjectorCollection
    {
        return new DependencyInjectorCollection();
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * @return array
     */
    protected function getDefaultGlueDependencyInjectorConfiguration(): array
    {
        return [
            'GlueApplication' => [
                'ProductsRestApi',
            ],
        ];
    }
}
