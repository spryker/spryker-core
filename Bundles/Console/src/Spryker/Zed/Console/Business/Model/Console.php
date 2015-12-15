<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Console\Business\Model;

use Psr\Log\LoggerInterface;
use Silex\Application;
use Spryker\Shared\Kernel\Messenger\MessengerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\ClassResolver\DependencyContainer\DependencyContainerNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\DependencyContainer\DependencyContainerResolver;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Spryker\Zed\Kernel\Communication\DependencyContainer\DependencyContainerInterface;
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
     * @var DependencyContainerInterface
     */
    private $dependencyContainer;

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
     * @param AbstractCommunicationDependencyContainer $dependencyContainer
     *
     * @return self
     */
    public function setDependencyContainer(AbstractCommunicationDependencyContainer $dependencyContainer)
    {
        $this->dependencyContainer = $dependencyContainer;

        return $this;
    }

    /**
     * @return AbstractCommunicationDependencyContainer
     */
    protected function getDependencyContainer()
    {
        if ($this->dependencyContainer === null) {
            $this->dependencyContainer = $this->resolveDependencyContainer();
        }

        if ($this->getQueryContainer() !== null) {
            $this->dependencyContainer->setQueryContainer($this->getQueryContainer());
        }

        if ($this->getContainer() !== null) {
            $this->dependencyContainer->setContainer($this->getContainer());
        }

        return $this->dependencyContainer;
    }

    /**
     * @throws DependencyContainerNotFoundException
     *
     * @return AbstractCommunicationDependencyContainer
     */
    private function resolveDependencyContainer()
    {
        $classResolver = new DependencyContainerResolver();

        return $classResolver->resolve($this);
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
    protected function resolveFacade()
    {
        return $this->getFacadeResolver()->resolve($this);
    }

    /**
     * @return FacadeResolver
     */
    protected function getFacadeResolver()
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
     * @return void
     */
    protected function runDependingCommand($command, array $arguments = [])
    {
        $this->setNewRelicTransaction($command, $arguments);

        $command = $this->getApplication()->find($command);
        $arguments['command'] = $command->getName();
        $input = new ArrayInput($arguments);

        $command->run($input, $this->output);
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
