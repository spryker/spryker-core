<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;

class ClassInfo
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
     * @return $this
     */
    public function setClass($callerClass)
    {
        if (is_object($callerClass)) {
            $callerClass = get_class($callerClass);
        }
        $this->callerClassName = $callerClass;

        $callerClassParts = [
            self::KEY_BUNDLE => $callerClass,
        ];

        if ($this->isFullyQualifiedClassName($callerClass)) {
            $callerClassParts = explode('\\', $callerClass);
            $callerClassParts = $this->removeTestNamespace($callerClassParts);
        }

        $this->callerClassParts = $callerClassParts;

        return $this;
    }

    /**
     * @param string $callerClass
     *
     * @return bool
     */
    private function isFullyQualifiedClassName($callerClass)
    {
        return (strpos($callerClass, '\\') !== false);
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
     * @return array
     */
    private function removeTestNamespace(array $callerClassParts)
    {
        $config = Config::getInstance();
        $projectNamespaces = $config->get(KernelConstants::PROJECT_NAMESPACES);
        $coreNamespaces = $config->get(KernelConstants::CORE_NAMESPACES);

        $namespaces = array_merge($projectNamespaces, $coreNamespaces);

        if (!in_array($callerClassParts[self::KEY_NAMESPACE], $namespaces)) {
            array_shift($callerClassParts);
        }

        return $callerClassParts;
    }

}
