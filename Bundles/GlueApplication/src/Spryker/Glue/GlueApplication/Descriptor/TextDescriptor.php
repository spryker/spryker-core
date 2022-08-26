<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Descriptor;

use Closure;
use InvalidArgumentException;
use ReflectionFunction;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\TableColumnExpanderPluginInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class TextDescriptor extends Descriptor
{
    /**
     * @var array<string>
     */
    protected const DEFAULT_TABLE_HEADERS = ['Name', 'Method', 'Scheme', 'Host', 'Path'];

    /**
     * @var string
     */
    protected const DEFAULT_CONTROLLER_HEADER = 'Controller';

    /**
     * @var string
     */
    protected const DEFAULTS_CONTROLLER = '_controller';

    /**
     * @var string
     */
    protected const OPTION_APPLICATION_NAME = 'application_name';

    /**
     * @var string
     */
    protected const OPTION_OUTPUT = 'output';

    /**
     * @var string
     */
    protected const OPTION_SHOW_CONTROLLERS = 'show_controllers';

    /**
     * @var string
     */
    protected const RESULT_ANY = 'ANY';

    /**
     * @var string
     */
    protected const RESULT_NONE = 'NONE';

    /**
     * @var string
     */
    protected const TEMPLATE_GLUE_APPLICATION = 'Glue%sApiApplication';

    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\TableColumnExpanderPluginInterface>
     */
    protected $tableColumnExpanderPlugins;

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\TableColumnExpanderPluginInterface> $tableColumnExpanderPlugins
     */
    public function __construct(array $tableColumnExpanderPlugins)
    {
        $this->tableColumnExpanderPlugins = $tableColumnExpanderPlugins;
    }

    /**
     * @param \Symfony\Component\Routing\RouteCollection $routes
     * @param array<string, mixed> $options
     *
     * @return void
     */
    protected function describeRouteCollection(RouteCollection $routes, array $options = []): void
    {
        $tableHeaders = $this->collectTableHeaders($options);
        $tableRows = $this->collectTableRows($routes, $options);

        if (isset($options[static::OPTION_OUTPUT])) {
            $options[static::OPTION_OUTPUT]->table($tableHeaders, $tableRows);

            return;
        }

        $table = new Table($this->getOutput());
        $table->setHeaders($tableHeaders)->setRows($tableRows);
        $table->render();
    }

    /**
     * @param \Symfony\Component\Routing\Route $route
     * @param array<string, mixed> $options
     *
     * @return void
     */
    protected function describeRoute(Route $route, array $options = []): void
    {
        $tableHeaders = ['Property', 'Value'];
        $tableRows = [
            ['Route Name', $options['name'] ?? ''],
            ['Path', $route->getPath()],
            ['Path Regex', $route->compile()->getRegex()],
            ['Host', ($route->getHost() !== '' ? $route->getHost() : static::RESULT_ANY)],
            ['Host Regex', ($route->getHost() !== '' ? $route->compile()->getHostRegex() : '')],
            ['Scheme', ($route->getSchemes() ? implode('|', $route->getSchemes()) : static::RESULT_ANY)],
            ['Method', ($route->getMethods() ? implode('|', $route->getMethods()) : static::RESULT_ANY)],
            ['Requirements', ($route->getRequirements() ? $this->formatRouterConfig($route->getRequirements()) : 'NO CUSTOM')],
            ['Class', get_class($route)],
            ['Defaults', $this->formatRouterConfig($route->getDefaults())],
            ['Options', $this->formatRouterConfig($route->getOptions())],
        ];

        foreach ($this->tableColumnExpanderPlugins as $tableColumnExpanderPlugin) {
            if (!$this->isCorrectApplicationFromOptions($tableColumnExpanderPlugin, $options)) {
                continue;
            }

            $tableRows[] = [
                $tableColumnExpanderPlugin->getHeader(),
                $tableColumnExpanderPlugin->getRowData($route),
            ];
        }

        $table = new Table($this->getOutput());
        $table->setHeaders($tableHeaders)->setRows($tableRows);
        $table->render();
    }

    /**
     * @param array $options
     *
     * @return array<string>
     */
    protected function collectTableHeaders(array $options): array
    {
        $showControllers = isset($options[static::OPTION_SHOW_CONTROLLERS]) && $options[static::OPTION_SHOW_CONTROLLERS];

        $tableHeaders = static::DEFAULT_TABLE_HEADERS;
        if ($showControllers) {
            $tableHeaders[] = static::DEFAULT_CONTROLLER_HEADER;
        }
        $tableHeaders = $this->expandTableHeaders($tableHeaders, $options);

        return $tableHeaders;
    }

    /**
     * @param array<string> $tableHeaders
     * @param array<string, mixed> $options
     *
     * @return array
     */
    protected function expandTableHeaders(array $tableHeaders, array $options): array
    {
        foreach ($this->tableColumnExpanderPlugins as $tableColumnExpanderPlugin) {
            if (!$this->isCorrectApplicationFromOptions($tableColumnExpanderPlugin, $options)) {
                continue;
            }

            $tableHeaders[] = $tableColumnExpanderPlugin->getHeader();
        }

        return $tableHeaders;
    }

    /**
     * @param \Symfony\Component\Routing\RouteCollection $routes
     * @param array $options
     *
     * @return array
     */
    protected function collectTableRows(RouteCollection $routes, array $options): array
    {
        $showControllers = isset($options[static::OPTION_SHOW_CONTROLLERS]) && $options[static::OPTION_SHOW_CONTROLLERS];

        $tableRows = [];
        foreach ($routes->all() as $name => $route) {
            $row = [
                $name,
                $route->getMethods() ? implode('|', $route->getMethods()) : static::RESULT_ANY,
                $route->getSchemes() ? implode('|', $route->getSchemes()) : static::RESULT_ANY,
                $route->getHost() !== '' ? $route->getHost() : static::RESULT_ANY,
                $route->getPath(),
            ];

            if ($showControllers) {
                $controller = $route->getDefault(static::DEFAULTS_CONTROLLER);
                $row[] = $controller ? $this->formatCallable($controller) : '';
            }

            $row = $this->expandTableRow($row, $route, $options);

            $tableRows[] = $row;
        }

        return $tableRows;
    }

    /**
     * @param array<string> $row
     * @param \Symfony\Component\Routing\Route $route
     * @param array<string, mixed> $options
     *
     * @return array<string>
     */
    protected function expandTableRow(array $row, Route $route, array $options): array
    {
        foreach ($this->tableColumnExpanderPlugins as $tableColumnExpanderPlugin) {
            if (!$this->isCorrectApplicationFromOptions($tableColumnExpanderPlugin, $options)) {
                continue;
            }

            $row[] = $tableColumnExpanderPlugin->getRowData($route);
        }

        return $row;
    }

    /**
     * @param array $config
     *
     * @return string
     */
    protected function formatRouterConfig(array $config): string
    {
        if (!$config) {
            return static::RESULT_NONE;
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
    protected function formatCallable($callable): string
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

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\TableColumnExpanderPluginInterface $plugin
     * @param array<string, mixed> $options
     *
     * @return bool
     */
    protected function isCorrectApplicationFromOptions(TableColumnExpanderPluginInterface $plugin, array $options): bool
    {
        $shouldCheckApplication = isset($options[static::OPTION_APPLICATION_NAME]) && $options[static::OPTION_APPLICATION_NAME];
        $argumentApplication = $options[static::OPTION_APPLICATION_NAME] ?? '';
        $argumentApplicationName = sprintf(
            static::TEMPLATE_GLUE_APPLICATION,
            ucfirst($argumentApplication),
        );

        $applicationName = $plugin->getApiApplicationName();
        if ($shouldCheckApplication && $argumentApplicationName !== $applicationName) {
            return false;
        }

        return true;
    }
}
