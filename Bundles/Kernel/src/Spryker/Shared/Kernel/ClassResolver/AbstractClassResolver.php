<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Kernel\Store;

abstract class AbstractClassResolver
{

    const KEY_NAMESPACE = '%namespace%';
    const KEY_BUNDLE = '%bundle%';
    const KEY_STORE = '%store%';

    /**
     * @var string
     */
    private $resolvedClassName;

    /**
     * @var array
     */
    private $classNames = [];

    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\ClassResolverCacheInterface
     */
    private static $cache;

    /**
     * @return string
     */
    abstract protected function getClassPattern();

    /**
     * @param string $namespace
     * @param string|null $store
     *
     * @return string
     */
    abstract protected function buildClassName($namespace, $store = null);

    /**
     * @return \Spryker\Shared\Kernel\ClassResolver\ClassResolverCacheInterface
     */
    protected function getCache()
    {
        if (self::$cache === null) {
            self::$cache = new ClassResolverCache();
        }

        return self::$cache;
    }

    /**
     * @return bool
     */
    public function canResolve()
    {
        $classNames = $this->buildClassNames();

        foreach ($classNames as $className) {
            if ($this->classExists($className)) {
                $this->resolvedClassName = $className;

                return true;
            }
        }

        return false;
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function classExists($className)
    {
        return self::getCache()->classExists($className);
    }

    /**
     * @return object
     */
    protected function getResolvedClassInstance()
    {
        return new $this->resolvedClassName();
    }

    /**
     * @return array
     */
    private function buildClassNames()
    {
        $this->addProjectClassNames();
        $this->addCoreClassNames();

        return $this->classNames;
    }

    /**
     * @return void
     */
    private function addProjectClassNames()
    {
        $storeName = Store::getInstance()->getStoreName();
        foreach ($this->getProjectNamespaces() as $namespace) {
            $this->classNames[] = $this->buildClassName($namespace, $storeName);
            $this->classNames[] = $this->buildClassName($namespace);
        }
    }

    /**
     * @return void
     */
    private function addCoreClassNames()
    {
        foreach ($this->getCoreNamespaces() as $namespace) {
            $this->classNames[] = $this->buildClassName($namespace);
        }
    }

    /**
     * @throws \Exception
     *
     * @return array
     */
    private function getProjectNamespaces()
    {
        return Config::getInstance()->get(KernelConstants::PROJECT_NAMESPACES);
    }

    /**
     * @throws \Exception
     *
     * @return array
     */
    private function getCoreNamespaces()
    {
        return Config::getInstance()->get(KernelConstants::CORE_NAMESPACES);
    }

}
