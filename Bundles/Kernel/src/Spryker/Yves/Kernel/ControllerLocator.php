<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Kernel;

use Spryker\Shared\Kernel\ClassMapFactory;
use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;
use Spryker\Shared\Kernel\Communication\ControllerLocatorInterface;

class ControllerLocator implements ControllerLocatorInterface
{

    /**
     * @var BundleControllerActionInterface
     */
    private $bundleControllerAction;

    /**
     * @var string
     */
    protected $application = 'Yves';

    /**
     * @var string
     */
    protected $layer = null;

    /**
     * @param BundleControllerActionInterface $bundleControllerAction
     */
    public function __construct(BundleControllerActionInterface $bundleControllerAction)
    {
        $this->bundleControllerAction = $bundleControllerAction;
    }

    /**
     * @return object
     */
    public function locate()
    {
        $resolvedController = ClassMapFactory::getInstance()->create(
            $this->application,
            $this->bundleControllerAction->getBundle(),
            'Controller' . $this->bundleControllerAction->getController() . 'Controller',
            $this->layer
        );

        return $resolvedController;
    }

    /**
     * @return bool
     */
    public function canLocate()
    {
        return ClassMapFactory::getInstance()->has(
            $this->application,
            $this->bundleControllerAction->getBundle(),
            'Controller' . $this->bundleControllerAction->getController() . 'Controller',
            $this->layer
        );
    }

}
