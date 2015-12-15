<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Communication;

use Spryker\Zed\Application\Communication\Plugin\Pimple;
use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;
use Spryker\Shared\Kernel\Communication\ControllerLocatorInterface;
use Spryker\Shared\Kernel\LocatorLocatorInterface;
use Spryker\Zed\Kernel\BundleDependencyProviderLocator;
use Spryker\Zed\Kernel\ClassNamePattern;
use Spryker\Zed\Kernel\Container;
use Spryker\Shared\Kernel\ClassMapFactory;
use Spryker\Shared\Library\Log;

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
     * @return object
     */
    public function locate(\Pimple $application, LocatorLocatorInterface $locator)
    {
        $resolvedController = ClassMapFactory::getInstance()->create(
            'Zed', $this->bundle, 'Controller' . $this->controller . 'Controller', 'Communication', [$application]
        );

        // @todo REFACTOR -  move to constructor when all controllers are upgraded
        $bundleName = lcfirst($this->bundle);

        if (!method_exists($resolvedController, 'setOwnFacade')) {
            Log::log($resolvedController, 'wrong_controller.txt');
        }

        $bundleConfigLocator = new BundleDependencyProviderLocator(); // @todo Make singleton because of performance
        $bundleBuilder = $bundleConfigLocator->locate($this->bundle, $locator);

        $container = new Container();
        $container = $this->addDefaultDependencies($container);

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
        return ClassMapFactory::getInstance()->has('Zed', $this->bundle, 'Controller' . $this->controller . 'Controller', 'Communication');
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addDefaultDependencies(Container $container)
    {
        $container[AbstractCommunicationDependencyContainer::FORM_FACTORY] = function (Container $container) {
            return (new Pimple())
                ->getApplication()[AbstractCommunicationDependencyContainer::FORM_FACTORY];
        };

        return $container;
    }

}
