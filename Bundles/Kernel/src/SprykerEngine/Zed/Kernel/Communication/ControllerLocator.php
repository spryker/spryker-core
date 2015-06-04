<?php

namespace SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Shared\Kernel\Communication\BundleControllerActionInterface;
use SprykerEngine\Shared\Kernel\Communication\ControllerLocatorInterface;
use SprykerEngine\Shared\Kernel\IdentityMapClassResolver;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Kernel\ClassResolver;
use SprykerEngine\Zed\Kernel\ClassNamePattern;

class ControllerLocator implements ControllerLocatorInterface
{

    /**
     * @var string
     */
    protected $bundle;

    /**
     * @var string
     */
    private $controllerPattern;

    /**
     * @var string
     */
    private $widgetControllerPattern;

    /**
     * @param BundleControllerActionInterface $bundleControllerAction
     * @param string $controllerNamePattern
     * @param string $widgetControllerNamePattern
     */
    public function __construct(
        BundleControllerActionInterface $bundleControllerAction,
        $controllerNamePattern = ClassNamePattern::CONTROLLER,
        $widgetControllerNamePattern = ClassNamePattern::CONTROLLER_WIDGET
    ) {
        $this->bundle = $bundleControllerAction->getBundle();
        $this->action = $bundleControllerAction->getAction();

        $this->controllerPattern = $this->preparePattern(
            $bundleControllerAction->getController(),
            $controllerNamePattern
        );
        $this->widgetControllerPattern = $this->preparePattern(
            $bundleControllerAction->getController(),
            $widgetControllerNamePattern
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
     * @param \Pimple $application
     * @param LocatorLocatorInterface $locator
     *
     * @return object
     * @throws ClassResolver\ClassNotFoundException
     */
    public function locate(\Pimple $application, LocatorLocatorInterface $locator)
    {
        $resolver = IdentityMapClassResolver::getInstance(new ClassResolver());
        $factory = new Factory($this->bundle);

        if ($resolver->canResolve($this->widgetControllerPattern, $this->bundle)) {
            $resolvedController = $resolver->resolve(
                $this->widgetControllerPattern,
                $this->bundle,
                [$application, $factory, $locator]
            );
        } else {
            $resolvedController = $resolver->resolve(
                $this->controllerPattern,
                $this->bundle,
                [$application, $factory, $locator]
            );
        }

        return $resolvedController;
    }

    /**
     * @return bool
     */
    public function canLocate()
    {
        $resolver = IdentityMapClassResolver::getInstance(new ClassResolver());

        $canResolveWidgetController = $resolver->canResolve($this->widgetControllerPattern, $this->bundle);
        $canResolveController = $resolver->canResolve($this->controllerPattern, $this->bundle);

        return $canResolveWidgetController || $canResolveController;
    }

}
