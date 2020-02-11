<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Communication\Plugin\Console\Descriptor;

use Closure;
use InvalidArgumentException;
use ReflectionFunction;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class TextDescriptor extends Descriptor
{
    /**
     * @param \Symfony\Component\Routing\RouteCollection $routes
     * @param array $options
     *
     * @return void
     */
    protected function describeRouteCollection(RouteCollection $routes, array $options = [])
    {
        $showControllers = isset($options['show_controllers']) && $options['show_controllers'];

        $tableHeaders = ['Name', 'Method', 'Scheme', 'Host', 'Path'];
        if ($showControllers) {
            $tableHeaders[] = 'Controller';
        }

        $tableRows = [];
        foreach ($routes->all() as $name => $route) {
            $row = [
                $name,
                $route->getMethods() ? implode('|', $route->getMethods()) : 'ANY',
                $route->getSchemes() ? implode('|', $route->getSchemes()) : 'ANY',
                $route->getHost() !== '' ? $route->getHost() : 'ANY',
                $route->getPath(),
            ];

            if ($showControllers) {
                $controller = $route->getDefault('_controller');
                $row[] = $controller ? $this->formatCallable($controller) : '';
            }

            $tableRows[] = $row;
        }

        if (isset($options['output'])) {
            $options['output']->table($tableHeaders, $tableRows);

            return;
        }

        $table = new Table($this->getOutput());
        $table->setHeaders($tableHeaders)->setRows($tableRows);
        $table->render();
    }

    /**
     * @param \Symfony\Component\Routing\Route $route
     * @param array $options
     *
     * @return void
     */
    protected function describeRoute(Route $route, array $options = [])
    {
        $tableHeaders = ['Property', 'Value'];
        $tableRows = [
            ['Route Name', isset($options['name']) ? $options['name'] : ''],
            ['Path', $route->getPath()],
            ['Path Regex', $route->compile()->getRegex()],
            ['Host', ($route->getHost() !== '' ? $route->getHost() : 'ANY')],
            ['Host Regex', ($route->getHost() !== '' ? $route->compile()->getHostRegex() : '')],
            ['Scheme', ($route->getSchemes() ? implode('|', $route->getSchemes()) : 'ANY')],
            ['Method', ($route->getMethods() ? implode('|', $route->getMethods()) : 'ANY')],
            ['Requirements', ($route->getRequirements() ? $this->formatRouterConfig($route->getRequirements()) : 'NO CUSTOM')],
            ['Class', get_class($route)],
            ['Defaults', $this->formatRouterConfig($route->getDefaults())],
            ['Options', $this->formatRouterConfig($route->getOptions())],
        ];

        $table = new Table($this->getOutput());
        $table->setHeaders($tableHeaders)->setRows($tableRows);
        $table->render();
    }

    /**
     * @param array $config
     *
     * @return string
     */
    private function formatRouterConfig(array $config): string
    {
        if (empty($config)) {
            return 'NONE';
        }

        ksort($config);

        $configAsString = '';
        foreach ($config as $key => $value) {
            $configAsString .= sprintf("\n%s: %s", $key, $this->formatValue($value));
        }

        return trim($configAsString);
    }

    /**
     * @param mixed $callable
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    private function formatCallable($callable): string
    {
        if (is_array($callable)) {
            if (is_object($callable[0])) {
                return sprintf('%s::%s()', get_class($callable[0]), $callable[1]);
            }

            return sprintf('%s::%s()', $callable[0], $callable[1]);
        }

        if (is_string($callable)) {
            return sprintf('%s()', $callable);
        }

        if ($callable instanceof Closure) {
            $r = new ReflectionFunction($callable);
            if (strpos($r->name, '{closure}') !== false) {
                return 'Closure()';
            }
            $class = $r->getClosureScopeClass();
            if ($class !== null) {
                return sprintf('%s::%s()', $class->name, $r->name);
            }

            return $r->name . '()';
        }

        if (method_exists($callable, '__invoke')) {
            return sprintf('%s::__invoke()', get_class($callable));
        }

        throw new InvalidArgumentException('Callable is not describable.');
    }
}
