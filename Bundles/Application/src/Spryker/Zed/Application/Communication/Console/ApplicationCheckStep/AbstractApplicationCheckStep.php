<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Console\ApplicationCheckStep;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Container;

abstract class AbstractApplicationCheckStep extends AbstractLogger implements LoggerAwareInterface, LoggerInterface
{

    use LoggerAwareTrait;

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected $facade;

    /**
     * @var \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory
     */
    protected $communicationFactory;

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        if ($this->logger) {
            $this->logger->log($level, $message, $context);
        }
    }

    /**
     * @param \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory $communicationFactory
     *
     * @return void
     */
    public function setCommunicationFactory(AbstractCommunicationFactory $communicationFactory)
    {
        $this->communicationFactory = $communicationFactory;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    public function setExternalDependencies(Container $container)
    {
        $communicationFactory = $this->getFactory();
        if (isset($communicationFactory)) {
            $this->getFactory()->setContainer($container);
        }
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    protected function getFactory()
    {
        return $this->communicationFactory;
    }

    /**
     * @param \Spryker\Zed\Kernel\Business\AbstractFacade $facade
     *
     * @return $this
     */
    public function setFacade(AbstractFacade $facade)
    {
        $this->facade = $facade;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        if ($this->facade === null) {
            $this->facade = $this->resolveFacade();
        }

        return $this->facade;
    }

    /**
     * @throws \Spryker\Zed\Kernel\ClassResolver\Facade\FacadeNotFoundException
     *
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
     * @return void
     */
    abstract public function run();

}
