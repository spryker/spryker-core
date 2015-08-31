<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Shared\Kernel\Communication\BundleControllerActionInterface;
use SprykerEngine\Shared\Kernel\Communication\ControllerLocatorInterface;
use SprykerEngine\Shared\Kernel\IdentityMapClassResolver;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Kernel\ClassResolver;
use SprykerEngine\Zed\Kernel\BundleDependencyProviderLocator;
use SprykerEngine\Zed\Kernel\ClassNamePattern;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Shared\Kernel\Factory2;

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
        $this->controller = $bundleControllerAction->getController();

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
     * @throws ClassResolver\ClassNotFoundException
     *
     * @return object
     */
    public function locate(\Pimple $application, LocatorLocatorInterface $locator)
    {
//        $resolver = IdentityMapClassResolver::getInstance(new ClassResolver());
        $factory = new Factory($this->bundle);

//        if ($resolver->canResolve($this->widgetControllerPattern, $this->bundle)) {
//            $resolvedController = $resolver->resolve(
//                $this->widgetControllerPattern,
//                $this->bundle,
//                [$application, $factory, $locator]
//            );
//        } else {
//            $resolvedController = $resolver->resolve(
//                $this->controllerPattern,
//                $this->bundle,
//                [$application, $factory, $locator]
//            );
//        }

        $resolvedController = Factory2::getInstance()->create('Zed', $this->bundle, 'Controller'.$this->controller.'Controller', 'Communication', [$application, $factory, $locator]);

        // @todo REFACTOR -  move to constructor when all controllers are upgraded
        $bundleName = lcfirst($this->bundle);

        if (!method_exists($resolvedController, 'setOwnFacade')) {
            \SprykerFeature_Shared_Library_Log::log($resolvedController, 'wrong_controller.txt');
        }

        $bundleConfigLocator = new BundleDependencyProviderLocator(); // @todo Make singleton because of performance
        $bundleBuilder = $bundleConfigLocator->locate($this->bundle, $locator);

        $container = new Container();
        $bundleBuilder->provideCommunicationLayerDependencies($container);
        $resolvedController->setExternalDependencies($container);

        // @todo make lazy
        if ($locator->$bundleName()->hasFacade()) {
            $resolvedController->setOwnFacade($locator->$bundleName()->facade());
        }

        // @todo make lazy
        if ($locator->$bundleName()->hasQueryContainer()) {
            $resolvedController->setOwnQueryContainer($locator->$bundleName()->queryContainer());
        }

        return $resolvedController;
    }

    /**
     * @return bool
     */
    public function canLocate()
    {

        return Factory2::getInstance()->has('Zed', $this->bundle, 'Controller'.$this->controller.'Controller', 'Communication');
//
//        $resolver = IdentityMapClassResolver::getInstance(new ClassResolver());
//
//        $canResolveWidgetController = $resolver->canResolve($this->widgetControllerPattern, $this->bundle);
//        $canResolveController = $resolver->canResolve($this->controllerPattern, $this->bundle);
//
//        return $canResolveWidgetController || $canResolveController;
    }

}
