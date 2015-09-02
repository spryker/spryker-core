<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel\Communication;

use SprykerEngine\Shared\Kernel\ClassMapFactory;
use SprykerEngine\Shared\Kernel\Communication\BundleControllerActionInterface;
use SprykerEngine\Shared\Kernel\Communication\ControllerLocatorInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

class ControllerLocator implements ControllerLocatorInterface
{

    /**
     * @var BundleControllerActionInterface
     */
    private $bundleControllerAction;

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
            'Yves', $this->bundleControllerAction->getBundle(), 'Controller' . $this->bundleControllerAction->getController() . 'Controller', 'Communication', [$application, $factory, $locator]
        );

        return $resolvedController;
    }

    /**
     * @return bool
     */
    public function canLocate()
    {
        return ClassMapFactory::getInstance()->has(
            'Yves', $this->bundleControllerAction->getBundle(), 'Controller' . $this->bundleControllerAction->getController() . 'Controller', 'Communication'
        );
    }

}
