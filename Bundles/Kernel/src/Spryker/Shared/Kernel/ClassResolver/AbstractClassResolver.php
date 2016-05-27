<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Library\Collection\Collection;

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
     * @var \Spryker\Shared\Library\Collection\CollectionInterface
     */
    protected static $unresolvableCollection;

    /**
     * @return string
     */
    abstract protected function getClassPattern();

    /**
     * @return bool
     */
    public function canResolve()
    {
        $classNames = $this->buildClassNames();

        foreach ($classNames as $className) {
            if ($this->getUnresolvableCollection()->has($className)) {

                return false;
            }

            if ($this->classExists($className)) {
                $this->resolvedClassName = $className;

                return true;
            }
            else {
                $this->getUnresolvableCollection()->set($className, true);
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
        return class_exists($className);
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
     * @param string $namespace
     * @param string|null $store
     *
     * @return string
     */
    abstract protected function buildClassName($namespace, $store = null);

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

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    private function getUnresolvableCollection()
    {
        if (self::$unresolvableCollection === null) {
            self::$unresolvableCollection = new Collection([]);
        }

        return self::$unresolvableCollection;
    }

}
