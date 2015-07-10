<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\ClassResolver\ClassNameAmbiguousException;
use SprykerEngine\Shared\Kernel\ClassResolver\ClassNotFoundException;
use SprykerEngine\Shared\Kernel\ClassResolver\ClassResolverInterface;
use SprykerEngine\Shared\Kernel\ClassResolver\InstanceBuilder;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;

class ClassResolver implements ClassResolverInterface
{

    /**
     * @var array
     */
    private $existsMap = [];

    const MESSAGE_CLASS_NAME_AMBIGUOUS =
        'It\'s not allowed to have the same bundle in two namespaces in one layer. Check: "%s"';

    /**
     * @param string $classNamePattern
     * @param string $bundle
     *
     * @throws ClassNameAmbiguousException
     *
     * @return bool
     */
    public function canResolve($classNamePattern, $bundle)
    {
        $class = $this->prepareClassName($classNamePattern, $bundle);
        $canBeResolved = $this->canBeResolvedIn($this->getProjectNamespaces(), $class);

        if (!$canBeResolved) {
            $canBeResolved = $this->canBeResolvedIn($this->getCoreNamespaces(), $class);
        }

        return $canBeResolved;
    }

    /**
     * @param string $classNamePattern
     * @param string $bundle
     *
     * @return string
     */
    private function prepareClassName($classNamePattern, $bundle)
    {
        $class = str_replace('{{bundle}}', $bundle, $classNamePattern);

        return $class;
    }

    /**
     * @param array $namespaces
     * @param string $class
     *
     * @throws ClassNameAmbiguousException
     *
     * @return array
     */
    private function canBeResolvedIn(array $namespaces, $class)
    {
        $resolvedClass = false;
        $resolvedInNamespace = null;

        foreach ($namespaces as $namespace) {
            foreach ($this->getStores() as $store) {
                $className = $this->buildClassName($namespace, $store, $class);
                if ($this->classExists($className)) {
                    if ($this->classAlreadyResolvedInSameNamespace($resolvedClass, $namespace, $resolvedInNamespace)) {
                        throw new ClassNameAmbiguousException(
                            sprintf(self::MESSAGE_CLASS_NAME_AMBIGUOUS, $class)
                        );
                    }
                    $resolvedClass = true;
                    $resolvedInNamespace = $namespace;
                }
            }
        }

        return $resolvedClass;
    }

    /**
     * @return array
     */
    private function getStores()
    {
        $stores = [
            \SprykerEngine\Shared\Kernel\Store::getInstance()->getStoreName(),
            '',
        ];

        return $stores;
    }

    /**
     * @param string $namespace
     * @param string $store
     * @param string $class
     *
     * @return string
     */
    private function buildClassName($namespace, $store, $class)
    {
        $className = str_replace(['{{namespace}}', '{{store}}'], [$namespace, $store], $class);

        return $className;
    }

    /**
     * @throws \Exception
     *
     * @return array
     */
    private function getProjectNamespaces()
    {
        return Config::get(SystemConfig::PROJECT_NAMESPACES);
    }

    /**
     * @throws \Exception
     *
     * @return array
     */
    private function getCoreNamespaces()
    {
        return Config::get(SystemConfig::CORE_NAMESPACES);
    }

    /**
     * @param string $classNamePattern
     * @param string $bundle
     * @param array $arguments
     *
     * @throws \Exception
     *
     * @return object
     */
    public function resolve($classNamePattern, $bundle, array $arguments = [])
    {
        $class = $this->prepareClassName($classNamePattern, $bundle);
        $resolvedClass = false;

        $resolvedClass = $this->resolveIn($this->getProjectNamespaces(), $class, $arguments);

        if (!$resolvedClass) {
            $resolvedClass = $this->resolveIn($this->getCoreNamespaces(), $class, $arguments);
        }

        if ($resolvedClass) {
            return $resolvedClass;
        }

        throw new ClassNotFoundException(
            sprintf('Could not resolve "%s" in "%s" bundle', $classNamePattern, $bundle)
        );
    }

    /**
     * @param array $namespaces
     * @param string $class
     * @param array $arguments
     *
     * @throws ClassNameAmbiguousException
     *
     * @return bool|object
     */
    private function resolveIn(array $namespaces, $class, array $arguments = [])
    {
        $resolvedClass = false;
        $resolvedInNamespace = null;

        foreach ($namespaces as $namespace) {
            foreach ($this->getStores() as $store) {
                $className = $this->buildClassName($namespace, $store, $class);
                if ($this->classExists($className)) {
                    if ($this->classAlreadyResolvedInSameNamespace($resolvedClass, $namespace, $resolvedInNamespace)) {
                        throw new ClassNameAmbiguousException(
                            sprintf(self::MESSAGE_CLASS_NAME_AMBIGUOUS, $class)
                        );
                    }
                    if (!$this->classAlreadyResolvedInStore($resolvedClass)) {
                        $resolvedClass = $this->createClass($className, $arguments);
                        $resolvedInNamespace = $namespace;
                    }
                }
            }
        }

        return $resolvedClass;
    }

    /**
     * @param object $resolvedClass
     * @param string $namespace
     * @param string $alreadyResolvedIn
     *
     * @return bool
     */
    private function classAlreadyResolvedInSameNamespace($resolvedClass, $namespace, $alreadyResolvedIn)
    {
        return ($resolvedClass !== false) && ($alreadyResolvedIn !== $namespace);
    }

    /**
     * @param object $resolvedClass
     *
     * @return bool
     */
    private function classAlreadyResolvedInStore($resolvedClass)
    {
        return $resolvedClass !== false;
    }

    /**
     * @param string $className
     * @param array $arguments
     *
     * @return object
     */
    private function createClass($className, array $arguments = [])
    {
        return (new InstanceBuilder())->createInstance($className, $arguments);
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    private function classExists($className)
    {
        if (isset($this->existsMap[$className])) {
            return $this->existsMap[$className];
        }

        return $this->existsMap[$className] = class_exists($className);
    }

}
