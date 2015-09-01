<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\ClassResolver\ClassNotFoundException;
use SprykerEngine\Shared\Kernel\ClassResolver\InstanceBuilder;

class ClassMapFactory
{

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
        global $map;
        include_once APPLICATION_ROOT_DIR . '/factory_class_map.php';
        $this->map = $map;
    }

    /**
     * @param $application
     * @param $bundle
     * @param $suffix
     * @param null $layer
     * @param array $arguments
     *
     * @throws ClassNotFoundException
     *
     * @return object
     */
    public function create($application, $bundle, $suffix, $layer = null, $arguments = [])
    {
        $key = $this->createKey($application, $bundle, $suffix, $layer);

        if (false === array_key_exists($key, $this->map)) {
            throw new ClassNotFoundException('Class ' . $suffix . ' does not exist');
        }
        $className = $this->map[$key];

        return (new InstanceBuilder())->createInstance($className, $arguments);
    }

    public function has($application, $bundle, $suffix, $layer = null)
    {
        $key = $this->createKey($application, $bundle, $suffix, $layer);

        return array_key_exists($key, $this->map);
    }

    /**
     * @param $application
     * @param $bundle
     * @param $suffix
     * @param $layer
     *
     * @return string
     */
    protected function createKey($application, $bundle, $suffix, $layer)
    {
        if (isset($layer)) {
            $key = implode('|', [$application, $bundle, $layer, $suffix]);

            return $key;
        } else {
            $key = implode('|', [$application, $bundle, $suffix]);

            return $key;
        }
    }

}
