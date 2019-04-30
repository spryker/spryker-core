<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\Router\Resource;

use Exception;
use ReflectionClass;
use Spryker\Zed\Kernel\ClassResolver\Controller\ControllerResolver;
use Spryker\Zed\Kernel\Communication\BundleControllerAction;
use Spryker\Zed\Router\Business\Resource\ResourceInterface;
use Spryker\Zed\Router\Business\Route\Route;
use Spryker\Zed\Router\Business\Route\RouteCollection;
use Spryker\Zed\Router\RouterConfig;
use Symfony\Component\Finder\Finder;
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToDash;

class RouterResource implements ResourceInterface
{
    /**
     * @var \Spryker\Zed\Router\RouterConfig
     */
    protected $config;

    /**
     * @var \Zend\Filter\FilterChain|null
     */
    protected $filterChain;

    /**
     * @param \Spryker\Zed\Router\RouterConfig $config
     */
    public function __construct(RouterConfig $config)
    {
        $this->config = $config;
    }

    /**
     * TODO: Cleanup this method
     *
     * @throws \Exception
     *
     * @return \Spryker\Zed\Router\Business\Route\RouteCollection
     */
    public function __invoke(): RouteCollection
    {
        $routeCollection = new RouteCollection();

        $finder = new Finder();
        $finder->files()->in($this->config->getControllerDirectories())->name('*Controller.php');

        $filterChain = $this->getFilterChain();

        foreach ($finder as $fileInfo) {
            $classNameParts = explode(DIRECTORY_SEPARATOR, $fileInfo->getPathname());
            $srcPosition = array_search('src', $classNameParts);
            $className = implode('\\', array_slice($classNameParts, $srcPosition + 1));
            $className = str_replace('.php', '', $className);

            if (!class_exists($className)) {
                throw new Exception(sprintf('Expected class "%s" not found!', $className));
            }

            $reflectedClass = new ReflectionClass($className);
            if (!$reflectedClass->isInstantiable()) {
                continue;
            }

            $methods = $reflectedClass->getMethods();

            foreach ($methods as $method) {
                if (!$method->isPublic() || strpos($method->getName(), 'Action') === false) {
                    continue;
                }

                $controllerNameParts = explode('\\', $className);

                $module = $controllerNameParts[2];
                $controller = str_replace('Controller', '', $controllerNameParts[5]);
                $action = str_replace('Action', '', $method->getName());

                $path = sprintf(
                    '/%s',
                    $filterChain->filter($module)
                );

                if ($filterChain->filter($controller) !== 'index' || $filterChain->filter($action) !== 'index') {
                    $path .= '/' . $filterChain->filter($controller);
                    if ($filterChain->filter($action) !== 'index') {
                        $path .= '/' . $filterChain->filter($action);
                    }
                }

                $template = sprintf(
                    '%s/%s/%s',
                    $module,
                    $filterChain->filter($controller),
                    $filterChain->filter($action)
                );

                $route = new Route($path);
                $routeName = sprintf(
                    '%s:%s:%s',
                    $filterChain->filter($module),
                    $filterChain->filter($controller),
                    $filterChain->filter($action)
                );

                $bundleControllerAction = new BundleControllerAction($module, $controller, $action);
                $controllerResolver = new ControllerResolver();
                /** @var \Spryker\Zed\Kernel\Communication\Controller\AbstractController $controller */
                $controller = $controllerResolver->resolve($bundleControllerAction);

                $route->addDefaults([
                    '_controller' => [get_class($controller), $method->getName()],
                    '_template' => $template,
                ]);

                $routeCollection->add($routeName, $route);
            }
        }

        $bundleControllerAction = new BundleControllerAction('Application', 'Index', 'index');
        $controllerResolver = new ControllerResolver();
        /** @var \Spryker\Zed\Kernel\Communication\Controller\AbstractController $controller */
        $controller = $controllerResolver->resolve($bundleControllerAction);

        $route = new Route('/');
        $routeName = 'application:index:index';

        $route->addDefaults([
            '_controller' => [get_class($controller), 'indexAction'],
            '_template' => 'Application/index/index',
        ]);

        $routeCollection->add($routeName, $route);

        return $routeCollection;
    }

    /**
     * @return \Zend\Filter\FilterChain
     */
    protected function getFilterChain(): FilterChain
    {
        if ($this->filterChain === null) {
            $this->filterChain = new FilterChain();
            $this->filterChain
                ->attach(new CamelCaseToDash())
                ->attach(new StringToLower());
        }

        return $this->filterChain;
    }
}
