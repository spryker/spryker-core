<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Communication;

use Spryker\Shared\ApplicationExtension\Dependency\Plugin\BootableApplicationPluginInterface;
use Spryker\Shared\Kernel\Communication\Application as SprykerApplication;
use Spryker\Zed\Console\Business\Model\Environment;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @deprecated Use {@link \Spryker\Zed\Console\Communication\Bootstrap\ConsoleBootstrap}
 *
 * @method \Spryker\Zed\Console\ConsoleConfig getConfig()
 */
class ConsoleBootstrap extends Application
{
    use BundleConfigResolverAwareTrait;

    /**
     * @var \Spryker\Zed\Console\Business\ConsoleFacadeInterface
     */
    protected $facade;

    /**
     * @var \Spryker\Shared\Kernel\Communication\Application
     */
    protected $application;

    /**
     * @var \Spryker\Shared\ApplicationExtension\Dependency\Plugin\BootableApplicationPluginInterface[]
     */
    protected $bootablePlugins = [];

    /**
     * @var bool
     */
    protected $booted = false;

    /**
     * @param string $name
     * @param string $version
     */
    public function __construct($name = 'Spryker', $version = '1')
    {
        Environment::initialize();

        parent::__construct($name, $version);
        $this->setCatchExceptions($this->getConfig()->shouldCatchExceptions());
        $this->addEventDispatcher();

        $this->application = new SprykerApplication();

        $this->registerServiceProviders();
        $this->provideApplicationPlugins();

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
    private function provideApplicationPlugins(): void
    {
        $applicationPlugins = $this->getFacade()->getApplicationPlugins();

        foreach ($applicationPlugins as $applicationPlugin) {
            $applicationPlugin->provide($this->application);

            if ($applicationPlugin instanceof BootableApplicationPluginInterface) {
                $this->bootablePlugins[] = $applicationPlugin;
            }
        }
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Console\Communication\ConsoleBootstrap::provideApplicationPlugins()} instead.
     *
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
     * Gets the default input definition.
     *
     * @return \Symfony\Component\Console\Input\InputDefinition An InputDefinition instance
     */
    protected function getDefaultInputDefinition()
    {
        $inputDefinitions = parent::getDefaultInputDefinition();
        $inputDefinitions->addOption(new InputOption('--no-pre', '', InputOption::VALUE_NONE, 'Will not execute pre run hooks'));
        $inputDefinitions->addOption(new InputOption('--no-post', '', InputOption::VALUE_NONE, 'Will not execute post run hooks'));
        $inputDefinitions->addOption(new InputOption('--quiet-meta', '', InputOption::VALUE_NONE, 'Disables meta output of store and environment'));

        return $inputDefinitions;
    }

    /**
     * @return \Spryker\Zed\Console\Business\ConsoleFacadeInterface
     */
    protected function getFacade()
    {
        if ($this->facade === null) {
            $this->facade = $this->resolveFacade();
        }

        return $this->facade;
    }

    /**
     * @return \Spryker\Zed\Console\Business\ConsoleFacadeInterface
     */
    protected function resolveFacade()
    {
        /** @var \Spryker\Zed\Console\Business\ConsoleFacadeInterface $facade */
        $facade = $this->getFacadeResolver()->resolve($this);

        return $facade;
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
        $this->setDecorated($output);

        if (!$input->hasParameterOption(['--format'], true) && !$input->hasParameterOption('--quiet-meta', true)) {
            $output->writeln($this->getInfoText());
        }

        $this->application->boot();

        if (!$this->booted) {
            $this->booted = true;
            $this->bootPlugins();
        }

        if (!$input->hasParameterOption(['--no-pre'], true)) {
            $this->getFacade()->preRun($input, $output);
        }

        $response = parent::doRun($input, $output);

        if (!$input->hasParameterOption(['--no-post'], true)) {
            $this->getFacade()->postRun($input, $output);
        }

        return $response;
    }

    /**
     * @return void
     */
    protected function bootPlugins(): void
    {
        foreach ($this->bootablePlugins as $bootablePlugin) {
            $bootablePlugin->boot($this->application);
        }
    }

    /**
     * @return string
     */
    protected function getInfoText()
    {
        return sprintf(
            '<fg=yellow>Code bucket</fg=yellow>: <info>%s</info> | <fg=yellow>Store</fg=yellow>: <info>%s</info> | <fg=yellow>Environment</fg=yellow>: <info>%s</info>',
            APPLICATION_CODE_BUCKET !== '' ? APPLICATION_CODE_BUCKET : 'N/A',
            defined('APPLICATION_STORE') ? APPLICATION_STORE : 'N/A',
            APPLICATION_ENV
        );
    }

    /**
     * This will force color mode when executed from another tool. The env variable can be set
     * from anybody who wants to force color mode for the execution of this Application.
     *
     * For Spryker's deploy tool it is needed to get colored output from the console commands
     * executed by this script without force projects to deal with ANSI Escape sequences of the underlying
     * console commands.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function setDecorated(OutputInterface $output)
    {
        if (getenv('FORCE_COLOR_MODE')) {
            $output->setDecorated(true);
        }
    }
}
