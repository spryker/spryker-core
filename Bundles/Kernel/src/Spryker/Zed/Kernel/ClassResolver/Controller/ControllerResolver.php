<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\ClassResolver\DependencyContainer;

use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver;

class ControllerResolver extends AbstractClassResolver
{

    const CLASS_NAME_PATTERN = '\\%s\\Zed\\%s%s\\Communication\\Controller\\%sController';

    const KEY_CONTROLLER = '%controller%';

    /**
     * @var string
     */
    protected $bundle;

    /**
     * @var string
     */
    protected $controller;

    /**
     * @param $bundle
     * @param $controller
     *
     * @throws ControllerNotFoundException
     *
     * @return object
     */
    public function resolve($bundle, $controller)
    {
        $this->bundle = $bundle;
        $this->controller = $controller;

        if ($this->canResolve()) {
            return $this->getResolvedClassInstance();
        }

        throw new ControllerNotFoundException($bundle, $controller);
    }

    /**
     * @return string
     */
    public function getClassPattern()
    {
        return sprintf(
            self::CLASS_NAME_PATTERN,
            self::KEY_NAMESPACE,
            self::KEY_BUNDLE,
            self::KEY_STORE,
            self::KEY_CONTROLLER
        );
    }

    /**
     * @param string $namespace
     * @param string|null $store
     *
     * @return string
     */
    protected function buildClassName($namespace, $store = null)
    {
        $searchAndReplace = [
            self::KEY_NAMESPACE => $namespace,
            self::KEY_BUNDLE => $this->bundle,
            self::KEY_STORE => $store,
            self::KEY_CONTROLLER => $this->controller,
        ];

        $className = str_replace(
            array_keys($searchAndReplace),
            array_values($searchAndReplace),
            $this->getClassPattern()
        );

        return $className;
    }

}
