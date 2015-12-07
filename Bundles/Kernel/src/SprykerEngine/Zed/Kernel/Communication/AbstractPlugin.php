<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Communication;

use Psr\Log\AbstractLogger;
use SprykerEngine\Shared\Kernel\Locator\LocatorInterface;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\BundleDependencyProviderLocator;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;

abstract class AbstractPlugin extends AbstractLogger implements MessengerInterface
{

    const DEPENDENCY_CONTAINER = 'DependencyContainer';

    /**
     * @var MessengerInterface
     */
    protected $messenger;

    /**
     * @var AbstractFacade
     */
    private $facade;

    /**
     * @var AbstractCommunicationDependencyContainer
     */
    private $dependencyContainer;

    /**
     * @var AbstractQueryContainer
     */
    private $queryContainer;

    /**
     * @param MessengerInterface $messenger
     *
     * @return self
     */
    public function setMessenger(MessengerInterface $messenger)
    {
        $this->messenger = $messenger;

        return $this;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return self
     */
    public function log($level, $message, array $context = [])
    {
        if ($this->messenger) {
            $this->messenger->log($level, $message, $context);
        }

        return $this;
    }

    /**
     * TODO remove method
     *
     * @param AbstractFacade $facade
     *
     * @return void
     */
    public function setOwnFacade(AbstractFacade $facade)
    {
//        $this->facade = $facade;
    }

    /**
     * For autocompletion use typehint in class docblock like this: "@method MyFacade getFacade()"
     *
     * @return AbstractFacade
     */
    protected function getFacade()
    {
        if ($this->facade === null) {
            $bundle = $this->getBundleName();

            $this->facade = $this->getLocator()->$bundle()->facade();
        }

        return $this->facade;
    }

    /**
     * TODO: remove method
     *
     * @param AbstractCommunicationDependencyContainer $dependencyContainer
     *
     * @return self
     */
    public function setDependencyContainer(AbstractCommunicationDependencyContainer $dependencyContainer)
    {
//        $this->dependencyContainer = $dependencyContainer;
//
//        return $this;
    }

    /**
     * @return AbstractCommunicationDependencyContainer
     */
    protected function getDependencyContainer()
    {
        if ($this->dependencyContainer === null) {
            $factory = new Factory($this->getBundleName());

            $this->dependencyContainer = $factory->create(self::DEPENDENCY_CONTAINER, $factory, $this->getLocator());

            $bundleConfigLocator = new BundleDependencyProviderLocator();
            $container = new Container();
            $bundleBuilder = $bundleConfigLocator->locate($this->getBundleName(), $this->getLocator());
            $bundleBuilder->provideCommunicationLayerDependencies($container);

            $this->dependencyContainer->setContainer($container);
        }

        return $this->dependencyContainer;
    }

    /**
     * TODO: remove method
     *
     * @param AbstractQueryContainer $queryContainer
     *
     * @return self
     */
    public function setQueryContainer(AbstractQueryContainer $queryContainer)
    {
//        $this->queryContainer = $queryContainer;
//
//        return $this;
    }

    /**
     * @return AbstractQueryContainer
     */
    protected function getQueryContainer()
    {
        if ($this->queryContainer === null) {
            $bundle = $this->getBundleName();
            $this->queryContainer = $this->getLocator()->$bundle()->queryContainer();
        }

        return $this->queryContainer;
    }

    /**
     * @return string
     */
    private function getBundleName()
    {
        $className = get_class($this);
        $expl = explode('\\', $className);
        $bundle = $expl[2];
        $bundle = lcfirst($bundle);

        return $bundle;
    }

    /**
     * @return LocatorInterface
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

}
