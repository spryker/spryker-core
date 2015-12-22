<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Kernel\ClassResolver\Controller;

use Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver;
use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;

abstract class AbstractControllerResolver extends AbstractClassResolver
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
     * @param BundleControllerActionInterface $bundleControllerAction
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

}
