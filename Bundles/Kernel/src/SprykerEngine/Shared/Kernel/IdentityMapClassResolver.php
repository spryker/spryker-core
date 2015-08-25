<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\ClassResolver\ClassNotFoundException;
use SprykerEngine\Shared\Kernel\ClassResolver\ClassResolverInterface;
use SprykerEngine\Shared\Kernel\ClassResolver\InstanceBuilder;

class IdentityMapClassResolver implements ClassResolverInterface
{

    const MAP_KEY_CAN_RESOLVE = 'can resolve result';
    const MAP_KEY_CLASS_NAME = 'resolved class name';

    /**
     * @var ClassResolverInterface
     */
    private $resolver;

    /**
     * @var self
     */
    private static $instance;

    /**
     * @var array
     */
    private $map = [];

    /**
     * @param ClassResolverInterface $resolver
     *
     * @return IdentityMapClassResolver
     */
    public static function getInstance(ClassResolverInterface $resolver)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        self::$instance->setResolver($resolver);

        return self::$instance;
    }

    protected function __construct()
    {
        $this->cache = new ClassResolverCache();
        $this->map = $this->cache->loadClassMap();
    }

    public function __destruct()
    {
        $this->cache->saveClassMap($this->map);
    }

    /**
     * @param ClassResolverInterface $resolver
     */
    private function setResolver(ClassResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param string $classNamePattern
     * @param string $bundle
     *
     * @return bool
     */
    public function canResolve($classNamePattern, $bundle)
    {
        $classNamePattern = $this->getPatternWithResolvedBundle($classNamePattern, $bundle);
        if (array_key_exists($classNamePattern, $this->map)) {
            return $this->map[$classNamePattern][self::MAP_KEY_CAN_RESOLVE];
        } else {
            $result = $this->resolver->canResolve($classNamePattern, $bundle);
            $this->map[$classNamePattern] = [
                self::MAP_KEY_CAN_RESOLVE => $result,
            ];

            return $result;
        }
    }

    /**
     * @param string $classNamePattern
     * @param string $bundle
     * @param array $arguments
     *
     * @throws ClassNotFoundException
     *
     * @return object
     */
    public function resolve($classNamePattern, $bundle, array $arguments = [])
    {
        $classNamePattern = $this->getPatternWithResolvedBundle($classNamePattern, $bundle);
        if (!$this->canResolve($classNamePattern, $bundle)) {
            throw new ClassNotFoundException(
                sprintf('Could not find "%s" in "%s" bundle', $classNamePattern, $bundle)
            );
        } elseif (array_key_exists(self::MAP_KEY_CLASS_NAME, $this->map[$classNamePattern])) {
            return $this->createClass($this->map[$classNamePattern][self::MAP_KEY_CLASS_NAME], $arguments);
        } else {
            $resolvedClass = $this->resolver->resolve($classNamePattern, $bundle, $arguments);
            $mapData = $this->map[$classNamePattern];
            $mapData[self::MAP_KEY_CLASS_NAME] = get_class($resolvedClass);
            $this->map[$classNamePattern] = $mapData;

            return $resolvedClass;
        }
    }

    /**
     * @param string $pattern
     * @param string $bundle
     *
     * @return string
     */
    private function getPatternWithResolvedBundle($pattern, $bundle)
    {
        return str_replace('{{bundle}}', $bundle, $pattern);
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

}
