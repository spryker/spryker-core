<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication\Controller;

use Generated\Shared\Transfer\MessageTransfer;
use Silex\Application;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver;
use Spryker\Zed\Kernel\Dependency\Facade\KernelToMessengerBridge;
use Spryker\Zed\Kernel\Dependency\Facade\NullMessenger;
use Spryker\Zed\Kernel\Exception\Controller\InvalidIdException;
use Spryker\Zed\Kernel\RepositoryResolverAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class AbstractController
{
    use RepositoryResolverAwareTrait;

    /**
     * @var \Silex\Application
     */
    private $application;

    /**
     * @var \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    private $factory;

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    private $facade;

    /**
     * @var \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer
     */
    private $queryContainer;

    /**
     * @return void
     */
    public function initialize()
    {
    }

    /**
     * @param \Silex\Application $application
     *
     * @return $this
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;

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

        return $this->factory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory|\Spryker\Zed\Kernel\AbstractFactory
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
     * @return \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer
     */
    protected function getQueryContainer()
    {
        if ($this->queryContainer === null) {
            $this->queryContainer = $this->resolveQueryContainer();
        }

        return $this->queryContainer;
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer
     */
    private function resolveQueryContainer()
    {
        return $this->getQueryContainerResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver
     */
    private function getQueryContainerResolver()
    {
        return new QueryContainerResolver();
    }

    /**
     * This methods centralizes the way we cast IDs. This is needed to allow the usage of UUIDs in the future.
     *
     * @param mixed $id
     *
     * @throws \Spryker\Zed\Kernel\Exception\Controller\InvalidIdException
     *
     * @return int
     */
    protected function castId($id)
    {
        if (!is_numeric($id) || $id === 0) {
            throw new InvalidIdException('The given id is not numeric or 0 (zero)');
        }

        return (int)$id;
    }

    /**
     * @param string $url
     * @param int $status
     * @param array $headers
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectResponse($url, $status = 302, $headers = [])
    {
        return new RedirectResponse($url, $status, $headers);
    }

    /**
     * @param array|null $data
     * @param int $status
     * @param array $headers
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function jsonResponse($data = null, $status = 200, $headers = [])
    {
        return new JsonResponse($data, $status, $headers);
    }

    /**
     * @param callable|null $callback
     * @param int $status
     * @param array $headers
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function streamedResponse($callback = null, $status = 200, $headers = [])
    {
        $streamedResponse = new StreamedResponse($callback, $status, $headers);
        $streamedResponse->send();

        return $streamedResponse;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function viewResponse(array $data = [])
    {
        return $data;
    }

    /**
     * @param string $message
     * @param array $data
     *
     * @return $this
     */
    protected function addSuccessMessage($message, array $data = [])
    {
        $this->getMessenger()->addSuccessMessage($this->createMessageTransfer($message, $data));

        return $this;
    }

    /**
     * @param string $message
     * @param array $data
     *
     * @return $this
     */
    protected function addInfoMessage($message, array $data = [])
    {
        $this->getMessenger()->addInfoMessage($this->createMessageTransfer($message, $data));

        return $this;
    }

    /**
     * @param string $message
     * @param array $data
     *
     * @return $this
     */
    protected function addErrorMessage($message, array $data = [])
    {
        $this->getMessenger()->addErrorMessage($this->createMessageTransfer($message, $data));

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Dependency\Facade\KernelToMessengerInterface
     */
    protected function getMessenger()
    {
        $messenger = ($this->application->offsetExists('messenger')) ? $this->application['messenger'] : new NullMessenger();
        $kernelToMessengerBridge = new KernelToMessengerBridge($messenger);

        return $kernelToMessengerBridge;
    }

    /**
     * @param string $message
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    private function createMessageTransfer($message, array $data = [])
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($message);
        $messageTransfer->setParameters($data);

        return $messageTransfer;
    }

    /**
     * @return \Silex\Application
     */
    protected function getApplication()
    {
        return $this->application;
    }
}
