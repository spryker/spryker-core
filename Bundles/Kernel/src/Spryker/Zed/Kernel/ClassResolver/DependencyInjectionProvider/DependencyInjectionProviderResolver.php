<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\ClassResolver\DependencyInjectionProvider;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver;
use Spryker\Shared\Kernel\Dependency\Injection\DependencyInjectionProviderCollection;
use Spryker\Shared\Kernel\Dependency\Injection\DependencyInjectionProviderInterface;

class DependencyInjectionProviderResolver extends AbstractClassResolver
{

    const CLASS_NAME_PATTERN = '\\%1$s\\Zed\\%2$s%3$s\\Dependency\\Injection\\%4$sDependencyInjector';
    const KEY_FROM_BUNDLE = '%fromBundle%';

    /**
     * @var string
     */
    private $fromBundle;

    /**
     * @param object|string $callerClass
     *
     * @return \Spryker\Shared\Kernel\Dependency\Injection\DependencyInjectionProviderCollectionInterface
     */
    public function resolve($callerClass)
    {
        $dependencyInjectionProviderCollection = new DependencyInjectionProviderCollection();

        $this->setCallerClass($callerClass);
        $injectToBundle = $this->getClassInfo()->getBundle();
        $injectFromBundles = $this->getInjectionBundles($injectToBundle);

        foreach ($injectFromBundles as $injectFromBundle) {
            $this->fromBundle = $injectFromBundle;

            if ($this->canResolve()) {
                $resolvedInjectionProvider = $this->getResolvedClassInstance();
                $dependencyInjectionProviderCollection->addDependencyInjectorProvider($resolvedInjectionProvider);
            }
        }

        return $dependencyInjectionProviderCollection;
    }

    /**
     * @return DependencyInjectionProviderInterface
     */
    protected function getResolvedClassInstance()
    {
        return parent::getResolvedClassInstance();
    }

    /**
     * @return string
     */
    public function getClassPattern()
    {
        return sprintf(
            self::CLASS_NAME_PATTERN,
            self::KEY_NAMESPACE,
            self::KEY_FROM_BUNDLE,
            self::KEY_STORE,
            self::KEY_BUNDLE
        );
    }

    /**
     * @param string $namespace
     * @param string|null $store
     *
     * @return string
     */
    protected function buildClassName($namespace, $store = null)
    {
        $searchAndReplace = [
            self::KEY_NAMESPACE => $namespace,
            self::KEY_BUNDLE => $this->getClassInfo()->getBundle(),
            self::KEY_FROM_BUNDLE => $this->fromBundle,
            self::KEY_STORE => $store,
        ];

        $className = str_replace(
            array_keys($searchAndReplace),
            array_values($searchAndReplace),
            $this->getClassPattern()
        );

        return $className;
    }

    /**
     * @param string $injectToBundle
     *
     * @return array
     */
    protected function getInjectionBundles($injectToBundle)
    {
        $injectionConfiguration = $this->getDependencyInjectionConfiguration();
        if (array_key_exists($injectToBundle, $injectionConfiguration)) {
            return $injectionConfiguration[$injectToBundle];
        }

        return [];
    }

    /**
     * @return array
     */
    protected function getDependencyInjectionConfiguration()
    {
        return Config::get(KernelConstants::DEPENDENCY_INJECTION_ZED, []);
    }

}
