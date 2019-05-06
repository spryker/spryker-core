<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Communication\Plugin\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Spryker\Zed\Router\Communication\Plugin\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\RouteCollection;

/**
 * @method \Spryker\Zed\Router\Business\RouterFacade getFacade()
 * @method \Spryker\Zed\Router\Communication\RouterCommunicationFactory getFactory()
 */
class RouterDebugZedConsole extends Console
{
    /**
     * @var string
     */
    protected static $defaultName = 'router:debug:zed';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setDefinition([
                new InputArgument('name', InputArgument::OPTIONAL, 'A route name'),
                new InputOption('show-controllers', null, InputOption::VALUE_NONE, 'Show assigned controllers in overview'),
            ])
            ->setDescription('Displays current routes for an application')
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> displays the configured routes:

  <info>php %command.full_name%</info>

EOF
            );
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException When route does not exist
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $helper = new DescriptorHelper();

        $router = $this->getFacade()->getRouter();
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
