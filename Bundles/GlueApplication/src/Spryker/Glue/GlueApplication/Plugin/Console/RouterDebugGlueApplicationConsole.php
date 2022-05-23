<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\Console;

use Spryker\Glue\Kernel\Console\Console;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouteCollection;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class RouterDebugGlueApplicationConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_DESCRIPTION = 'Renders a table with all available routes for specified Glue Application.';

    /**
     * @var string
     */
    protected const NAME = 'router:debug';

    /**
     * @var string
     */
    protected const NAME_ALIAS = 'r:d';

    /**
     * @var string
     */
    protected const ARGUMENT_GLUE_APPLICATION_NAME = 'application_name';

    /**
     * @var string
     */
    protected const OPTION_SHOW_CONTROLLERS = 'show-controllers';

    /**
     * @var string
     */
    protected const OPTION_SHOW_CONTROLLERS_SHORT = 'c';

    /**
     * @var string
     */
    protected const OPTION_ROUTE_NAME = 'route-name';

    /**
     * @var string
     */
    protected const OPTION_ROUTE_NAME_SHORT = 'r';

    /**
     * @var \Spryker\Glue\GlueApplication\Plugin\Console\Helper\DescriptorHelper
     */
    protected $descriptorHelper;

    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    protected $io;

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;

        $factory = $this->getFactory();
        $this->io = $factory->createConsoleOutputStyle($input, $output);
        $this->descriptorHelper = $factory->createDescriptorHelper();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::NAME)
            ->setAliases([static::NAME_ALIAS])
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setDefinition([
                new InputArgument(static::ARGUMENT_GLUE_APPLICATION_NAME, InputArgument::REQUIRED, 'A Glue Application name.'),
                new InputOption(static::OPTION_ROUTE_NAME, static::OPTION_ROUTE_NAME_SHORT, InputOption::VALUE_OPTIONAL, 'Show a route name.'),
                new InputOption(static::OPTION_SHOW_CONTROLLERS, static::OPTION_SHOW_CONTROLLERS_SHORT, InputOption::VALUE_NONE, 'Show assigned controllers in the overview.'),
            ]);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $argumentApplicationName = $this->input->getArgument(static::ARGUMENT_GLUE_APPLICATION_NAME);
        $argumentApplicationName = $this->normalizeOption($argumentApplicationName);

        $routerProviderPlugins = $this->getFactory()->getGlueApplicationRouterProviderPlugins();
        foreach ($routerProviderPlugins as $plugin) {
            $applicationName = $this->normalizeOption($plugin->getApiApplicationName());
            if (strpos($applicationName, $argumentApplicationName) === false) {
                continue;
            }

            $routes = $plugin->getRouteCollection();

            $this->describeRoutes($routes);
        }

        return static::CODE_SUCCESS;
    }

    /**
     * @param \Symfony\Component\Routing\RouteCollection $routes
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     *
     * @return void
     */
    protected function describeRoutes(RouteCollection $routes): void
    {
        /** @var string|null $routeName */
        $routeName = $this->input->getOption(static::OPTION_ROUTE_NAME);

        if ($routeName === null) {
            $this->descriptorHelper->describe($this->io, $routes, [
                'show_controllers' => $this->input->getOption(static::OPTION_SHOW_CONTROLLERS),
                'output' => $this->io,
            ]);

            return;
        }

        $route = $routes->get($routeName);
        if (!$route) {
            $matchingRoutes = $this->findRouteNameContaining($routeName, $routes);
            if ($matchingRoutes) {
                $default = count($matchingRoutes) === 1 ? $matchingRoutes[0] : null;
                $routeName = $this->io->choice('Select one of the matching routes', $matchingRoutes, $default);
                $route = $routes->get($routeName);
            }
        }

        if (!$route) {
            throw new InvalidArgumentException(sprintf('The route %s" does not exist.', $routeName));
        }

        $this->descriptorHelper->describe($this->io, $route, [
            'name' => $routeName,
            'output' => $this->io,
        ]);
    }

    /**
     * @param string $name
     * @param \Symfony\Component\Routing\RouteCollection $routes
     *
     * @return array
     */
    protected function findRouteNameContaining(string $name, RouteCollection $routes): array
    {
        $foundRoutesNames = [];
        foreach ($routes as $routeName => $route) {
            if (stripos($routeName, $name) !== false) {
                $foundRoutesNames[] = $routeName;
            }
        }

        return $foundRoutesNames;
    }

    /**
     * @param mixed $option
     *
     * @return string|null
     */
    protected function normalizeOption($option): ?string
    {
        if ($option === null) {
            return null;
        }

        return strtolower($option);
    }
}
