<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\Controller;

use Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver;
use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;

/**
 * @method \Spryker\Zed\Kernel\Communication\Controller\AbstractController getResolvedClassInstance()
 */
abstract class AbstractControllerResolver extends AbstractClassResolver
{
    const KEY_CONTROLLER = '%controller%';

    /**
     * @var \Spryker\Shared\Kernel\Communication\BundleControllerActionInterface
     */
    protected $bundleControllerAction;

    /**
     * @param \Spryker\Shared\Kernel\Communication\BundleControllerActionInterface $bundleControllerAction
     *
     * @throws \Spryker\Shared\Kernel\ClassResolver\Controller\ControllerNotFoundException
     *
     * @return object
     */
    public function resolve(BundleControllerActionInterface $bundleControllerAction)
    {
        $this->bundleControllerAction = $bundleControllerAction;
        if ($this->canResolve()) {
            return $this->getResolvedClassInstance();
        }

        throw new ControllerNotFoundException($bundleControllerAction);
    }

    /**
     * @param \Spryker\Shared\Kernel\Communication\BundleControllerActionInterface $bundleControllerAction
     *
     * @return bool
     */
    public function isResolveAble(BundleControllerActionInterface $bundleControllerAction)
    {
        $this->bundleControllerAction = $bundleControllerAction;
        if ($this->canResolve()) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getClassPattern()
    {
        return sprintf(
            $this->getClassNamePattern(),
            self::KEY_NAMESPACE,
            self::KEY_BUNDLE,
            self::KEY_STORE,
            self::KEY_CONTROLLER
        );
    }

    /**
     * @return string
     */
    abstract protected function getClassNamePattern();

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
            self::KEY_BUNDLE => ucfirst($this->bundleControllerAction->getBundle()),
            self::KEY_STORE => $store,
            self::KEY_CONTROLLER => ucfirst($this->bundleControllerAction->getController()),
        ];

        $className = str_replace(
            array_keys($searchAndReplace),
            array_values($searchAndReplace),
            $this->getClassPattern()
        );

        return $className;
    }
}
