<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Kernel\ClassResolver;

use Spryker\Shared\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Application\ApplicationConfig;

abstract class AbstractClassResolver
{

    /**
     * @var string
     */
    private $resolvedClassName;

    /**
     * @var array
     */
    private $classNames = [];

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
    abstract protected function buildClassName($namespace, $store = null);

    /**
     * @throws \Exception
     *
     * @return array
     */
    private function getProjectNamespaces()
    {
        return Config::getInstance()->get(ApplicationConfig::PROJECT_NAMESPACES);
    }

    /**
     * @throws \Exception
     *
     * @return array
     */
    private function getCoreNamespaces()
    {
        return Config::getInstance()->get(ApplicationConfig::CORE_NAMESPACES);
    }

}
