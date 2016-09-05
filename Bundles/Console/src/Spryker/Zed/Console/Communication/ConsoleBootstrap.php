<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Communication;

use Spryker\Shared\NewRelic\Api;
use Spryker\Zed\Console\Business\Model\Environment;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleBootstrap extends Application
{

    /**
     * @var \Spryker\Zed\Console\Business\ConsoleFacade
     */
    private $facade;

    /**
     * @param string $name
     * @param string $version
     */
    public function __construct($name = 'Spryker', $version = '1')
    {
        Environment::initialize();

        parent::__construct($name, $version);
        $this->setCatchExceptions(false);
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
        $newRelicApi = $this->getNewRelicApi();
        $newRelicApi->markAsBackgroundJob(true);
        $newRelicApi->setNameOfTransaction($this->getCommandTransactionName($input));

        $output->writeln($this->getInfoText());

        return parent::doRun($input, $output);
    }

    /**
     * @return \Spryker\Shared\NewRelic\ApiInterface
     */
    protected function getNewRelicApi()
    {
        return new Api();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string
     */
    protected function getCommandTransactionName(InputInterface $input)
    {
        return 'vendor/bin/console ' . (string)$input;
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
