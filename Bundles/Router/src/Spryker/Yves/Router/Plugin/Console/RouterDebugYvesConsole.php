<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Plugin\Console;

use Spryker\Yves\Router\Plugin\Console\Helper\DescriptorHelper;
use Spryker\Yves\Router\RouterFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\RouteCollection;

class RouterDebugYvesConsole extends Command
{
    private const NAME = 'router:debug';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::NAME)
            ->setAliases(['router:debug:yves'])
            ->setDefinition([
                new InputArgument('name', InputArgument::OPTIONAL, 'A route name.'),
                new InputOption('show-controllers', 'c', InputOption::VALUE_NONE, 'Show assigned controllers in the overview.'),
            ]);
    }

    /**
     * @return \Spryker\Yves\Router\RouterFactory
     */
    protected function getFactory(): RouterFactory
    {
        return new RouterFactory();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $helper = new DescriptorHelper();

        $router = $this->getFactory()->createRouter();
        $routes = $router->getRouteCollection();

        if ($name) {
            if (!($route = $routes->get($name)) && $matchingRoutes = $this->findRouteNameContaining($name, $routes)) {
                $default = count($matchingRoutes) === 1 ? $matchingRoutes[0] : null;
                $name = $io->choice('Select one of the matching routes', $matchingRoutes, $default);
                $route = $routes->get($name);
            }

            if (!$route) {
                throw new InvalidArgumentException(sprintf('The route "%s" does not exist.', $name));
            }

            $helper->describe($io, $route, [
                'name' => $name,
                'output' => $io,
            ]);
        } else {
            $helper->describe($io, $routes, [
                'show_controllers' => $input->getOption('show-controllers'),
                'output' => $io,
            ]);
        }
    }

    /**
     * @param string $name
     * @param \Symfony\Component\Routing\RouteCollection $routes
     *
     * @return array
     */
    private function findRouteNameContaining(string $name, RouteCollection $routes): array
    {
        $foundRoutesNames = [];
        foreach ($routes as $routeName => $route) {
            if (stripos($routeName, $name) !== false) {
                $foundRoutesNames[] = $routeName;
            }
        }

        return $foundRoutesNames;
    }
}
