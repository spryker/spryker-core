<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */
namespace Spryker\Shared\Kernel\ClassResolver;

use Spryker\Shared\Config;
use Spryker\Shared\Application\ApplicationConstants;

abstract class AbstractClassInfo
{

    const KEY_NAMESPACE = 0;
    const KEY_APPLICATION = 1;
    const KEY_BUNDLE = 2;

    /**
     * @var string
     */
    private $callerClassName;

    /**
     * @var string
     */
    protected $callerClassParts;

    /**
     * @param object|string $callerClass
     *
     * @return self
     */
    public function setClass($callerClass)
    {
        if (is_object($callerClass)) {
            $callerClass = get_class($callerClass);
        }
        $this->callerClassName = $callerClass;

        if (strpos('\\', $callerClass) === false) {
            $callerClassParts = [
                self::KEY_BUNDLE => $callerClass,
            ];
        } else {
            $callerClassParts = explode('\\', $callerClass);
            $callerClassParts = $this->removeTestNamespace($callerClassParts);
        }

        $this->callerClassParts = $callerClassParts;

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
     * @return string
     */
    public function getNamespace()
    {
        return $this->callerClassParts[self::KEY_NAMESPACE];
    }

    /**
     * @return string
     */
    public function getApplication()
    {
        return $this->callerClassParts[self::KEY_APPLICATION];
    }

    /**
     * @return string
     */
    public function getBundle()
    {
        return $this->callerClassParts[self::KEY_BUNDLE];
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
        $projectNamespaces = $config->get(ApplicationConstants::PROJECT_NAMESPACES);
        $coreNamespaces = $config->get(ApplicationConstants::CORE_NAMESPACES);

        $namespaces = array_merge($projectNamespaces, $coreNamespaces);

        if (!in_array($callerClassParts[self::KEY_NAMESPACE], $namespaces)) {
            array_shift($callerClassParts);
        }

        return $callerClassParts;
    }

}
