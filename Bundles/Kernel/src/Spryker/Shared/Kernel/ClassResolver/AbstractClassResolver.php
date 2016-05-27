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
use Spryker\Shared\Library\DataDirectory;

abstract class AbstractClassResolver
{

    const KEY_NAMESPACE = '%namespace%';
    const KEY_BUNDLE = '%bundle%';
    const KEY_STORE = '%store%';

    /**
     * @var string
     */
    protected $resolvedClassName;

    /**
     * @var array
     */
    protected $classNames = [];

    /**
     * @var \Spryker\Shared\Library\Collection\CollectionInterface
     */
    protected static $unresolvableCollection;

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
     * @return bool
     */
    public function canResolve()
    {
        $classNames = $this->buildClassNames();

        foreach ($classNames as $className) {
            if (self::getUnresolvableCollection()->has($className)) {
                continue;
            }

            if ($this->classExists($className)) {
                $this->resolvedClassName = $className;

                return true;
            } else {
                self::getUnresolvableCollection()->set($className, true);
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
    protected function buildClassNames()
    {
        $this->addProjectClassNames();
        $this->addCoreClassNames();

        return $this->classNames;
    }

    /**
     * @return void
     */
    protected function addProjectClassNames()
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
    protected function addCoreClassNames()
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
    protected function getProjectNamespaces()
    {
        return Config::getInstance()->get(KernelConstants::PROJECT_NAMESPACES);
    }

    /**
     * @throws \Exception
     *
     * @return array
     */
    protected function getCoreNamespaces()
    {
        return Config::getInstance()->get(KernelConstants::CORE_NAMESPACES);
    }

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    protected static function getUnresolvableCollection()
    {
        if (self::$unresolvableCollection === null) {
            self::$unresolvableCollection = new Collection([]);
            self::loadCache();
        }

        return self::$unresolvableCollection;
    }

    /**
     * @return void
     */
    public static function persistCache()
    {
        try {
            file_put_contents(self::getCacheFilename(), json_encode(
                self::getUnresolvableCollection()->toArray())
            );
        }
        catch (\Exception $e) {

        }
    }

    /**
     * @return void
     */
    public static function loadCache()
    {
        try {
            $data = json_decode(file_get_contents(
                self::getCacheFilename()
            ), true);

            if (is_array($data)) {
                self::getUnresolvableCollection()->collect($data);
            }
        }
        catch (\Exception $e) {

        }
    }

    /**
     * @return string
     */
    protected static function getCacheFilename()
    {
        return DataDirectory::getLocalStoreSpecificPath('cache/autoloader').'/unresolvable.json';
    }

}
