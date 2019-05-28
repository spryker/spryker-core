<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\Router\Resource;

use Exception;
use ReflectionClass;
use ReflectionMethod;
use Spryker\Zed\Kernel\ClassResolver\Controller\ControllerResolver;
use Spryker\Zed\Kernel\Communication\BundleControllerAction;
use Spryker\Zed\Router\Business\Resource\ResourceInterface;
use Spryker\Zed\Router\Business\Route\Route;
use Spryker\Zed\Router\Business\Route\RouteCollection;
use Spryker\Zed\Router\RouterConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToDash;

class RouterResource implements ResourceInterface
{
    public const MODULE_NAME_POSITION = 2;
    public const CONTROLLER_NAME_POSITION = 5;

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
     * @return \Spryker\Zed\Router\Business\Route\RouteCollection
     */
    public function __invoke(): RouteCollection
    {
        $routeCollection = new RouteCollection();

        $finder = new Finder();
        $finder->files()->in($this->config->getControllerDirectories())->name('*Controller.php');

        foreach ($finder as $fileInfo) {
            $routeCollection = $this->addRoutesForFile($fileInfo, $routeCollection);
        }

        return $routeCollection;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     * @param \Spryker\Zed\Router\Business\Route\RouteCollection $routeCollection
     *
     * @throws \Exception
     *
     * @return \Spryker\Zed\Router\Business\Route\RouteCollection
     */
    protected function addRoutesForFile(SplFileInfo $fileInfo, RouteCollection $routeCollection): RouteCollection
    {
        $className = $this->getClassNameFromFile($fileInfo);

        if (!class_exists($className)) {
            throw new Exception(sprintf('Expected class "%s" not found!', $className));
        }

        $reflectedClass = new ReflectionClass($className);
        if (!$reflectedClass->isInstantiable()) {
            return $routeCollection;
        }

        $methods = $reflectedClass->getMethods();

        foreach ($methods as $method) {
            $routeCollection = $this->addRoutesForMethod($method, $className, $routeCollection);
        }

        return $routeCollection;
    }

    /**
     * @param \ReflectionMethod $method
     * @param string $className
     * @param \Spryker\Zed\Router\Business\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Zed\Router\Business\Route\RouteCollection
     */
    protected function addRoutesForMethod(ReflectionMethod $method, string $className, RouteCollection $routeCollection): RouteCollection
    {
        if (!$method->isPublic() || strpos($method->getName(), 'Action') === false) {
            return $routeCollection;
        }

        $controllerNameParts = explode('\\', $className);

        $module = $controllerNameParts[static::MODULE_NAME_POSITION];
        $controller = str_replace('Controller', '', $controllerNameParts[static::CONTROLLER_NAME_POSITION]);
        $action = str_replace('Action', '', $method->getName());

        $template = $this->getTemplate($module, $controller, $action);
        $controllerClassName = $this->getControllerClassName($module, $controller, $action);

        $pathCandidates = $this->getPathCandidates($module, $controller, $action);

        foreach ($pathCandidates as $pathCandidate) {
            $routeCollection = $this->addRouteToCollection($method, $routeCollection, $pathCandidate, $controllerClassName, $template);
        }

        return $routeCollection;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return string
     */
    protected function getClassNameFromFile(SplFileInfo $fileInfo): string
    {
        $classNameParts = explode(DIRECTORY_SEPARATOR, $fileInfo->getPathname());
        $srcPosition = array_search('src', $classNameParts);
        $className = implode('\\', array_slice($classNameParts, $srcPosition + 1));
        $className = str_replace('.php', '', $className);

        return $className;
    }

    /**
     * @param string $module
     * @param string $controller
     * @param string $action
     *
     * @return string
     */
    protected function getTemplate(string $module, string $controller, string $action): string
    {
        $template = sprintf(
            '%s/%s/%s',
            $module,
            $controller,
            $this->getFilterChain()->filter($action)
        );

        return $template;
    }

    /**
     * @param string $module
     * @param string $controller
     * @param string $action
     *
     * @return string
     */
    protected function getControllerClassName(string $module, string $controller, string $action): string
    {
        $bundleControllerAction = new BundleControllerAction($module, $controller, $action);
        $controllerResolver = new ControllerResolver();
        /** @var \Spryker\Zed\Kernel\Communication\Controller\AbstractController $controller */
        $controller = $controllerResolver->resolve($bundleControllerAction);

        return get_class($controller);
    }

    /**
     * @param string $module
     * @param string $controller
     * @param string $action
     *
     * @return array
     */
    protected function getPathCandidates(string $module, string $controller, string $action): array
    {
        $module = $this->getFilterChain()->filter($module);
        $controller = $this->getFilterChain()->filter($controller);
        $action = $this->getFilterChain()->filter($action);

        $pathCandidates = [
            sprintf('/%s/%s/%s', $module, $controller, $action),
        ];

        if ($action === 'index') {
            $pathCandidates[] = sprintf('/%s/%s', $module, $controller);
        }

        if ($controller === 'index' && $action === 'index') {
            $pathCandidates[] = sprintf('/%s', $module);
        }

        if ($module === 'application' && $controller === 'index' && $action === 'index') {
            $pathCandidates[] = '/';
        }

        return $pathCandidates;
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

    /**
     * @param \ReflectionMethod $method
     * @param \Spryker\Zed\Router\Business\Route\RouteCollection $routeCollection
     * @param string $pathCandidate
     * @param string $controllerClassName
     * @param string $template
     *
     * @return \Spryker\Zed\Router\Business\Route\RouteCollection
     */
    protected function addRouteToCollection(ReflectionMethod $method, RouteCollection $routeCollection, string $pathCandidate, string $controllerClassName, string $template): RouteCollection
    {
        $route = new Route($pathCandidate);

        $route->addDefaults([
            '_controller' => [$controllerClassName, $method->getName()],
            '_template' => $template,
        ]);

        $routeName = str_replace('/', ':', trim($pathCandidate, '/'));
        if ($routeName === '') {
            $routeName = 'home';
        }

        $routeCollection->add($routeName, $route);

        return $routeCollection;
    }
}
