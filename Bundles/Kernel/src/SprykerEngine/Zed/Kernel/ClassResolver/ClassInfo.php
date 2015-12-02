<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerEngine\Zed\Kernel\ClassResolver;

class ClassInfo
{

    const KEY_NAMESPACE = 0;
    const KEY_APPLICATION = 1;
    const KEY_BUNDLE = 2;
    const KEY_LAYER = 3;

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
        $callerClass = get_class($callerClass);
        $callerClassParts = explode('\\', $callerClass);

        $this->namespace = $this->getNamespaceFromCallerClass($callerClassParts);
        $this->application = $this->getApplicationFromCallerClass($callerClassParts);
        $this->bundle = $this->getBundleFromCallerClass($callerClassParts);
        $this->layer = $this->getLayerFromCallerClass($callerClassParts);

        return $this;
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


}
