<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Business\Model;

use Silex\Application;
use Spryker\Shared\Library\System;
use Spryker\Shared\NewRelic\Api;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Console\Communication\ConsoleBootstrap getApplication()
 */
class Console extends SymfonyCommand
{

    use Helper;

    const CODE_SUCCESS = 0;
    const CODE_ERROR = 1;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @var \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    private $factory;

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    private $facade;

    /**
     * @var \Spryker\Zed\Kernel\Container
     */
    private $container;

    /**
     * @var \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer
     */
    private $queryContainer;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $messenger;

    /**
     * @var int
     */
    private $exitCode = self::CODE_SUCCESS;

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return $this
     */
    public function setExternalDependencies(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getContainer()
    {
        return $this->container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory $factory
     *
     * @return $this
     */
    public function setFactory(AbstractCommunicationFactory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        if ($this->container !== null) {
            $this->factory->setContainer($this->container);
        }

        if ($this->queryContainer !== null) {
            $this->factory->setQueryContainer($this->queryContainer);
        }

        return $this->factory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    private function resolveFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }

    /**
     * @param \Spryker\Zed\Kernel\Business\AbstractFacade $facade
     *
     * @return void
     */
    public function setFacade(AbstractFacade $facade)
    {
        $this->facade = $facade;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->resolveFacade();
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    private function resolveFacade()
    {
        return $this->getFacadeResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver
     */
    private function getFacadeResolver()
    {
        return new FacadeResolver();
    }

    /**
     * @param \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer $queryContainer
     *
     * @return $this
     */
    public function setQueryContainer(AbstractQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer
     */
    protected function getQueryContainer()
    {
        return $this->queryContainer;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $propelService = new PropelServiceProvider();
        $propelService->boot(new Application());
    }

    /**
     * @param string $command
     * @param array $arguments
     *
     * @return int
     */
    protected function runDependingCommand($command, array $arguments = [])
    {
        $this->setNewRelicTransaction($command, $arguments);

        $command = $this->getApplication()->find($command);
        $arguments['command'] = $command->getName();
        $input = new ArrayInput($arguments);

        $exitCode = $command->run($input, $this->output);

        $this->setExitCode($exitCode);

        return $exitCode;
    }

    /**
     * @param int $exitCode
     *
     * @return $this
     */
    private function setExitCode($exitCode)
    {
        $this->exitCode = $exitCode;

        return $this;
    }

    /**
     * @return bool
     */
    protected function hasError()
    {
        return $this->exitCode !== self::CODE_SUCCESS;
    }

    /**
     * @return int
     */
    protected function getLastExitCode()
    {
        return $this->exitCode;
    }

    /**
     * @return \Spryker\Zed\Messenger\Business\Model\MessengerInterface
     */
    protected function getMessenger()
    {
        if ($this->messenger === null) {
            $this->messenger = new ConsoleMessenger($this->output);
        }

        return $this->messenger;
    }

    /**
     * @param string $command
     * @param array $arguments
     *
     * @return void
     */
    protected function setNewRelicTransaction($command, array $arguments)
    {
        $newRelicApi = new Api();

        $newRelicApi
            ->markAsBackgroundJob()
            ->setNameOfTransaction($command)
            ->addCustomParameter('host', System::getHostname());

        foreach ($arguments as $key => $value) {
            $newRelicApi->addCustomParameter($key, $value);
        }
    }

}
