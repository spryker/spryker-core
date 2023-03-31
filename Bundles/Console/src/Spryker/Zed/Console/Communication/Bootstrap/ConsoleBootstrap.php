<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Communication\Bootstrap;

use Spryker\Zed\Console\Business\Model\Environment;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\Kernel\Communication\FacadeResolverAwareTrait;
use Spryker\Zed\Kernel\Communication\FactoryResolverAwareTrait;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Console\ConsoleConfig getConfig()
 * @method \Spryker\Zed\Console\Communication\ConsoleCommunicationFactory getFactory()
 * @method \Spryker\Zed\Console\Business\ConsoleFacade getFacade()
 */
class ConsoleBootstrap extends Application
{
    use BundleConfigResolverAwareTrait;
    use FactoryResolverAwareTrait;
    use FacadeResolverAwareTrait;

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
    }

    /**
     * @return void
     */
    protected function addEventDispatcher()
    {
        $eventDispatcher = $this->getFactory()->createEventDispatcher();
        $eventSubscriber = $this->getFactory()->getEventSubscriber();

        foreach ($eventSubscriber as $subscriber) {
            $eventDispatcher->addSubscriber($subscriber);
        }

        $this->setDispatcher($eventDispatcher);
    }

    /**
     * @return array<\Symfony\Component\Console\Command\Command>
     */
    protected function getDefaultCommands(): array
    {
        $commands = parent::getDefaultCommands();

        $locatedCommands = $this->getFactory()->getConsoleCommands();

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
    protected function getDefaultInputDefinition(): InputDefinition
    {
        $inputDefinitions = parent::getDefaultInputDefinition();
        $inputDefinitions->addOption(new InputOption('--no-pre', '', InputOption::VALUE_NONE, 'Will not execute pre run hooks'));
        $inputDefinitions->addOption(new InputOption('--no-post', '', InputOption::VALUE_NONE, 'Will not execute post run hooks'));
        $inputDefinitions->addOption(new InputOption('--quiet-meta', '', InputOption::VALUE_NONE, 'Disables meta output of store and environment'));

        return $inputDefinitions;
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
            $output->writeln($this->getInfoText($input));
        }

        $this->printDevVmDeprecationInfoText($output);

        $this->getFactory()
            ->createApplication()
            ->boot();

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
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string
     */
    protected function getInfoText(InputInterface $input): string
    {
        $infoTextData = [];

        if (defined('APPLICATION_REGION')) {
            $infoTextData[] = sprintf(
                '<fg=yellow>Region</fg=yellow>: <info>%s</info>',
                APPLICATION_REGION,
            );
        }

        $store = $this->getStore($input);

        if ($store) {
            $infoTextData[] = sprintf(
                '<fg=yellow>Store</fg=yellow>: <info>%s</info>',
                $store,
            );
        }

        if (defined('APPLICATION_CODE_BUCKET') && APPLICATION_CODE_BUCKET) {
            $infoTextData[] = sprintf(
                '<fg=yellow>Code bucket</fg=yellow>: <info>%s</info>',
                APPLICATION_CODE_BUCKET,
            );
        }

        $infoTextData[] = sprintf(
            '<fg=yellow>Environment</fg=yellow>: <info>%s</info>',
            APPLICATION_ENV,
        );

        return implode(' | ', $infoTextData);
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

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function printDevVmDeprecationInfoText(OutputInterface $output): void
    {
        if ($this->isDevVmEnv()) {
            $output->writeln('<fg=yellow;options=bold>---------------------------------</>');
            $output->writeln('<bg=yellow;options=bold>Warning! DevVM is depricated.</>');
            $output->writeln('<bg=yellow;options=bold>Please use docker environment.</>');
            $output->writeln('<fg=yellow;options=bold>---------------------------------</>');
        }
    }

    /**
     * @return bool
     */
    protected function isDevVmEnv(): bool
    {
        return getenv('USER') === 'vagrant';
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string
     */
    protected function getStore(InputInterface $input): string
    {
        $store = '';
        if ($input->hasParameterOption(['store'], true)) {
            $store = $input->getParameterOption('store');
        }

        if (!$store && defined('APPLICATION_STORE')) {
            $store = APPLICATION_STORE;
        }

        return $store;
    }
}
