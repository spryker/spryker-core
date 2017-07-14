<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Communication;

use Spryker\Shared\Kernel\Communication\Application as SprykerApplication;
use Spryker\Zed\Console\Business\Model\Environment;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ConsoleBootstrap extends Application
{

    /**
     * @var \Spryker\Zed\Console\Business\ConsoleFacade
     */
    protected $facade;

    /**
     * @var \Spryker\Shared\Kernel\Communication\Application
     */
    protected $application;

    /**
     * @param string $name
     * @param string $version
     */
    public function __construct($name = 'Spryker', $version = '1')
    {
        Environment::initialize();

        parent::__construct($name, $version);
        $this->setCatchExceptions(false);
        $this->addEventDispatcher();

        $this->application = new SprykerApplication();

        $this->registerServiceProviders();

        Pimple::setApplication($this->application);
    }

    /**
     * @return void
     */
    protected function addEventDispatcher()
    {
        $eventDispatcher = new EventDispatcher();
        $eventSubscriber = $this->getFacade()->getEventSubscriber();
        foreach ($eventSubscriber as $subscriber) {
            $eventDispatcher->addSubscriber($subscriber);
        }
        $this->setDispatcher($eventDispatcher);
    }

    /**
     * @return void
     */
    private function registerServiceProviders()
    {
        $serviceProviders = $this->getFacade()->getServiceProviders();

        foreach ($serviceProviders as $serviceProvider) {
            $this->application->register($serviceProvider);
        }
    }

    /**
     * @return \Symfony\Component\Console\Command\Command[]
     */
    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();

        $locatedCommands = $this->getFacade()->getConsoleCommands();

        foreach ($locatedCommands as $command) {
            $commands[$command->getName()] = $command;
        }

        return $commands;
    }

    /**
     * @return \Spryker\Zed\Console\Business\ConsoleFacade
     */
    protected function getFacade()
    {
        if ($this->facade === null) {
            $this->facade = $this->resolveFacade();
        }

        return $this->facade;
    }

    /**
     * @return \Spryker\Zed\Console\Business\ConsoleFacade
     */
    protected function resolveFacade()
    {
        return $this->getFacadeResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver
     */
    protected function getFacadeResolver()
    {
        return new FacadeResolver();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->getInfoText());

        $this->application->boot();

        return parent::doRun($input, $output);
    }

    /**
     * @return string
     */
    protected function getInfoText()
    {
        return sprintf(
            '<fg=yellow>Store</fg=yellow>: <info>%s</info> | <fg=yellow>Environment</fg=yellow>: <info>%s</info>',
            APPLICATION_STORE,
            APPLICATION_ENV
        );
    }

}
