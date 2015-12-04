<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel;

use SprykerEngine\Shared\Kernel\ClassMapFactory;
use SprykerEngine\Shared\Kernel\Communication\BundleControllerActionInterface;
use SprykerEngine\Shared\Kernel\Communication\ControllerLocatorInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Yves\Kernel\Factory;

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
     * @param \Pimple $application
     * @param LocatorLocatorInterface $locator
     *
     * @return object
     */
    public function locate(\Pimple $application, LocatorLocatorInterface $locator)
    {
        $factory = new Factory($this->bundleControllerAction->getBundle());

        $resolvedController = ClassMapFactory::getInstance()->create(
            $this->application,
            $this->bundleControllerAction->getBundle(),
            'Controller' . $this->bundleControllerAction->getController() . 'Controller',
            $this->layer,
            [$application, $factory, $locator]
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
