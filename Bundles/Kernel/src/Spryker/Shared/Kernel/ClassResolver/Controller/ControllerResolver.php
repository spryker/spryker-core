<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Kernel\ClassResolver\DependencyContainer;

use Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver;
use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;

class ControllerResolver extends AbstractClassResolver
{

    const KEY_CONTROLLER = '%controller%';

    /**
     * @var BundleControllerActionInterface
     */
    protected $bundleControllerAction;

    /**
     * @param BundleControllerActionInterface $bundleControllerAction
     *
     * @throws ControllerNotFoundException
     * @return object
     */
    public function resolve(BundleControllerActionInterface $bundleControllerAction)
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
