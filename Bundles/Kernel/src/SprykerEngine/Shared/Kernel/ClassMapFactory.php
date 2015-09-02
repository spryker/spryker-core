<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\ClassResolver\ClassNotFoundException;
use SprykerEngine\Shared\Kernel\ClassResolver\InstanceBuilder;

class ClassMapFactory
{

    const CLASS_MAP_FILE_NAME = '.class_map';

    /**
     * @var ClassMapFactory
     */
    protected static $instance;

    /**
     * @var array
     */
    protected $map;

    /**
     * @return ClassMapFactory
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->map = include_once APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . self::CLASS_MAP_FILE_NAME;
    }

    /**
     * @param string $application
     * @param string $bundle
     * @param string $suffix
     * @param string $layer
     * @param array $arguments
     *
     * @throws ClassNotFoundException
     *
     * @return object
     */
    public function create($application, $bundle, $suffix, $layer = null, $arguments = [])
    {
        $key = $this->createKey($application, $bundle, $suffix, $layer);

        if (!array_key_exists($key, $this->map)) {
            throw new ClassNotFoundException('Class "' . $suffix . '" does not exist');
        }
        $className = $this->map[$key];

        return (new InstanceBuilder())->createInstance($className, $arguments);
    }

    /**
     * @param string $application
     * @param string $bundle
     * @param string $suffix
     * @param string $layer
     *
     * @return bool
     */
    public function has($application, $bundle, $suffix, $layer = null)
    {
        $key = $this->createKey($application, $bundle, $suffix, $layer);

        return array_key_exists($key, $this->map);
    }

    /**
     * @param string $application
     * @param string $bundle
     * @param string $suffix
     * @param string $layer
     *
     * @return string
     */
    protected function createKey($application, $bundle, $suffix, $layer)
    {
        if (null !== $layer) {
            $key = implode('|', [$application, $bundle, $layer, $suffix]);
        } else {
            $key = implode('|', [$application, $bundle, $suffix]);
        }

        return $key;
    }

}
