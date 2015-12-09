<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerEngine\Zed\Kernel\ClassResolver;

use SprykerEngine\Shared\Config;
use SprykerFeature\Shared\System\SystemConfig;

class ClassInfo
{

    const KEY_NAMESPACE = 0;
    const KEY_APPLICATION = 1;
    const KEY_BUNDLE = 2;
    const KEY_LAYER = 3;

    /**
     * @var string
     */
    private $callerClassName;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $application;

    /**
     * @var string
     */
    private $bundle;

    /**
     * @var string
     */
    private $layer;

    /**
     * @param object $callerClass
     *
     * @return self
     */
    public function setClass($callerClass)
    {
        $callerClassName = get_class($callerClass);
        $this->callerClassName = $callerClassName;
        $callerClassParts = explode('\\', $callerClassName);

        $callerClassParts = $this->removeTestNamespace($callerClassParts);

        $this->namespace = $this->getNamespaceFromCallerClass($callerClassParts);
        $this->application = $this->getApplicationFromCallerClass($callerClassParts);
        $this->bundle = $this->getBundleFromCallerClass($callerClassParts);
        $this->layer = $this->getLayerFromCallerClass($callerClassParts);

        return $this;
    }

    /**
     * @return string
     */
    public function getCallerClassName()
    {
        return $this->callerClassName;
    }

    /**
     * @param array $callerClassParts
     *
     * @return string
     */
    private function getNamespaceFromCallerClass(array $callerClassParts)
    {
        return $callerClassParts[self::KEY_NAMESPACE];
    }

    /**
     * @param array $callerClassParts
     *
     * @return string
     */
    private function getApplicationFromCallerClass(array $callerClassParts)
    {
        return $callerClassParts[self::KEY_APPLICATION];
    }

    /**
     * @param array $callerClassParts
     *
     * @return string
     */
    private function getBundleFromCallerClass(array $callerClassParts)
    {
        return $callerClassParts[self::KEY_BUNDLE];
    }

    /**
     * @param array $callerClassParts
     *
     * @return string
     */
    private function getLayerFromCallerClass(array $callerClassParts)
    {
        return $callerClassParts[self::KEY_LAYER];
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @return string
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * @return string
     */
    public function getLayer()
    {
        return $this->layer;
    }

    /**
     * @TODO find a better way to get around this. Problem is that when we extend classes and use this class to
     * extract the needed elements, we will get namespaces like Unit, Functional, ClientUnit etc
     *
     * @param array $callerClassParts
     *
     * @throws \Exception
     *
     * @return array
     */
    private function removeTestNamespace(array $callerClassParts)
    {
        $config = Config::getInstance();
        $projectNamespaces = $config->get(SystemConfig::PROJECT_NAMESPACES);
        $coreNamespaces = $config->get(SystemConfig::CORE_NAMESPACES);

        $namespaces = array_merge($projectNamespaces, $coreNamespaces);

        if (!in_array($callerClassParts[self::KEY_NAMESPACE], $namespaces)) {
            array_shift($callerClassParts);
        }

        return $callerClassParts;
    }

}
