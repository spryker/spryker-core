<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Console\Business\Model;

use Psr\Log\LoggerInterface;
use Silex\Application;
use Spryker\Shared\Kernel\Messenger\MessengerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;
use Spryker\Shared\Library\System;
use Spryker\Shared\NewRelic\Api;
use Spryker\Zed\Console\Communication\ConsoleBootstrap;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method ConsoleBootstrap getApplication()
 */
class Console extends SymfonyCommand
{

    use Helper;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var AbstractCommunicationFactory
     */
    private $factory;

    /**
     * @var AbstractFacade
     */
    private $facade;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var AbstractQueryContainer
     */
    private $queryContainer;

    /**
     * @var LoggerInterface
     */
    protected $messenger;

    /**
     * @var int
     */
    private $exitCode = 0;

    /**
     * @param Container $container
     *
     * @return self
     */
    public function setExternalDependencies(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return Container
     */
    protected function getContainer()
    {
        return $this->container;
    }

    /**
     * @param AbstractCommunicationFactory $factory
     *
     * @return self
     */
    public function setFactory(AbstractCommunicationFactory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return AbstractCommunicationFactory
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
     * @throws FactoryNotFoundException
     *
     * @return AbstractCommunicationFactory
     */
    private function resolveFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }

    /**
     * @param AbstractFacade $facade
     *
     * @return void
     */
    public function setFacade(AbstractFacade $facade)
    {
        $this->facade = $facade;
    }

    /**
     * @return AbstractFacade
     */
    protected function getFacade()
    {
        return $this->resolveFacade();
    }

    /**
     * @throws FacadeNotFoundException
     *
     * @return AbstractFacade
     */
    private function resolveFacade()
    {
        return $this->getFacadeResolver()->resolve($this);
    }

    /**
     * @return FacadeResolver
     */
    private function getFacadeResolver()
    {
        return new FacadeResolver();
    }

    /**
     * @param AbstractQueryContainer $queryContainer
     *
     * @return self
     */
    public function setQueryContainer(AbstractQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;

        return $this;
    }

    /**
     * @return AbstractQueryContainer
     */
    protected function getQueryContainer()
    {
        return $this->queryContainer;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
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
     * @throws \Exception
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
     * @param $exitCode
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
        return $this->exitCode !== 0;
    }

    /**
     * @return int
     */
    protected function getLastExitCode()
    {
        return $this->exitCode;
    }

    /**
     * @return MessengerInterface
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
