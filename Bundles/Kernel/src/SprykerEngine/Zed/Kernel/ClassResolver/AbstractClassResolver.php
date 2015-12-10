<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\ClassResolver;

use SprykerEngine\Shared\Config;
use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Shared\System\SystemConfig;

abstract class AbstractClassResolver
{

    const KEY_NAMESPACE = '%namespace%';
    const KEY_APPLICATION = '%application%';
    const KEY_BUNDLE = '%bundle%';
    const KEY_LAYER = '%layer%';
    const KEY_STORE = '%store%';

    /**
     * @var ClassInfo
     */
    private $classInfo;

    /**
     * @var string
     */
    private $resolvedClassName;

    /**
     * @var array
     */
    private $classNames = [];

    public function __construct()
    {
        $this->classInfo = new ClassInfo();
    }

    /**
     * @param object|string $callerClass
     *
     * @return AbstractClassResolver
     */
    public function setCallerClass($callerClass)
    {
        $this->classInfo->setClass($callerClass);

        return $this;
    }

    /**
     * @return ClassInfo
     */
    public function getClassInfo()
    {
        return $this->classInfo;
    }

    /**
     * @return string
     */
    abstract protected function getClassPattern();

    /**
     * @return bool
     */
    protected function canResolve()
    {
        $classNames = $this->buildClassNames();

        foreach ($classNames as $className) {
            if (class_exists($className)) {
                $this->resolvedClassName = $className;

                return true;
            }
        }

        return false;
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
     * @param string $store
     *
     * @return string
     */
    private function buildClassName($namespace, $store = null)
    {
        $searchAndReplace = [
            self::KEY_NAMESPACE => $namespace,
            self::KEY_APPLICATION => $this->classInfo->getApplication(),
            self::KEY_BUNDLE => $this->classInfo->getBundle(),
            self::KEY_LAYER => $this->classInfo->getLayer(),
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
     * @throws \Exception
     *
     * @return array
     */
    private function getProjectNamespaces()
    {
        return Config::getInstance()->get(SystemConfig::PROJECT_NAMESPACES);
    }

    /**
     * @throws \Exception
     *
     * @return array
     */
    private function getCoreNamespaces()
    {
        return Config::getInstance()->get(SystemConfig::CORE_NAMESPACES);
    }

}
