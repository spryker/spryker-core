<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Console\Business\Model;

use Psr\Log\LoggerInterface;
use Silex\Application;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\DependencyContainer\DependencyContainerInterface;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\PropelServiceProvider;
use SprykerFeature\Zed\Console\Communication\ConsoleBootstrap;
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
     * @var AbstractQueryContainer
     */
    private $queryContainer;

    /**
     * @var LoggerInterface
     */
    protected $messenger;

    /**
     * @param Container $container
     */
    public function setExternalDependencies(Container $container)
    {
        $dependencyContainer = $this->getDependencyContainer();
        if (isset($dependencyContainer)) {
            $this->getDependencyContainer()->setContainer($container);
        }
    }

    /**
     * @param AbstractCommunicationDependencyContainer $dependencyContainer
     */
    public function setDependencyContainer(AbstractCommunicationDependencyContainer $dependencyContainer)
    {
        $this->dependencyContainer = $dependencyContainer;
    }

    /**
     * @return AbstractCommunicationDependencyContainer
     */
    protected function getDependencyContainer()
    {
        return $this->dependencyContainer;
    }

    /**
     * @param AbstractFacade $facade
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
        return $this->facade;
    }

    /**
     * @param AbstractQueryContainer $queryContainer
     *
     * @return $this
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
     */
    protected function runDependingCommand($command, array $arguments = [])
    {
        $command = $this->getApplication()->find($command);
        $arguments['command'] = $command;
        $input = new ArrayInput($arguments);
        $command->run($input, $this->output);
    }

    /**
     * @return MessengerInterface
     */
    protected function getMessenger()
    {
        if (is_null($this->messenger)) {
            $this->messenger = new ConsoleMessenger($this->output);
        }

        return $this->messenger;
    }

}
