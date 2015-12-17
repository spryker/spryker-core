<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Communication;

use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;
use Spryker\Shared\Kernel\Communication\ControllerLocatorInterface;
use Spryker\Zed\Kernel\ClassNamePattern;
use Spryker\Shared\Kernel\ClassMapFactory;

class ControllerLocator implements ControllerLocatorInterface
{

    /**
     * @var string
     */
    protected $bundle;

    /**
     * @var string
     */
    protected $controller;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    private $controllerPattern;

    /**
     * @param BundleControllerActionInterface $bundleControllerAction
     * @param string $controllerNamePattern
     */
    public function __construct(
        BundleControllerActionInterface $bundleControllerAction,
        $controllerNamePattern = ClassNamePattern::CONTROLLER
    ) {
        $this->bundle = $bundleControllerAction->getBundle();
        $this->action = $bundleControllerAction->getAction();
        $this->controller = $bundleControllerAction->getController();

        $this->controllerPattern = $this->preparePattern(
            $bundleControllerAction->getController(),
            $controllerNamePattern
        );
    }

    /**
     * @param string $controller
     * @param string $pattern
     *
     * @return string
     */
    private function preparePattern($controller, $pattern)
    {
        return str_replace('{{controller}}', $controller, $pattern);
    }

    /**
     * @return object
     */
    public function locate()
    {
        $resolvedController = ClassMapFactory::getInstance()->create(
            'Zed', $this->bundle, 'Controller' . $this->controller . 'Controller', 'Communication'
        );

        return $resolvedController;
    }

    /**
     * @return bool
     */
    public function canLocate()
    {
        return ClassMapFactory::getInstance()->has('Zed', $this->bundle, 'Controller' . $this->controller . 'Controller', 'Communication');
    }

}
