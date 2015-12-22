<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Controller;

use Generated\Shared\Transfer\MessageTransfer;
use Silex\Application;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver;
use Spryker\Zed\Messenger\Business\MessengerFacade;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Communication\CommunicationFactoryInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class AbstractController
{

    const TWIG_MESSENGER_PLUGIN = 'TwigMessengerPlugin';

    /**
     * @var Application
     */
    private $application;

    /**
     * @var AbstractCommunicationFactory
     */
    private $factory;

    /**
     * @var AbstractFacade
     */
    private $facade;

    /**
     * @var AbstractQueryContainer
     */
    private $queryContainer;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var MessengerFacade
     */
    private $messengerFacade;

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
        $this->messengerFacade = Locator::getInstance()->messenger()->facade();
    }

    /**
     * @return CommunicationFactoryInterface
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        if ($this->queryContainer !== null) {
            $this->factory->setQueryContainer($this->queryContainer);
        }

        if ($this->container !== null) {
            $this->factory->setContainer($this->container);
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
     * @return AbstractFacade
     */
    protected function getFacade()
    {
        if ($this->facade === null) {
            $this->facade = $this->resolveFacade();
        }
        return $this->facade;
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
        if ($this->queryContainer === null) {
            $this->queryContainer = $this->resolveQueryContainer();
        }

        return $this->queryContainer;
    }

    /**
     * @throws QueryContainerNotFoundException
     *
     * @return AbstractQueryContainer
     */
    private function resolveQueryContainer()
    {
        return $this->getQueryContainerResolver()->resolve($this);
    }

    /**
     * @return QueryContainerResolver
     */
    private function getQueryContainerResolver()
    {
        return new QueryContainerResolver();
    }

    /**
     * @param string $url
     * @param int $status
     * @param array $headers
     *
     * @return RedirectResponse
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
     * @return JsonResponse
     */
    protected function jsonResponse($data = null, $status = 200, $headers = [])
    {
        return new JsonResponse($data, $status, $headers);
    }

    /**
     * @param null $callback
     * @param int $status
     * @param array $headers
     *
     * @return StreamedResponse
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
     * @return self
     */
    protected function addSuccessMessage($message, array $data = [])
    {
        $this->messengerFacade->addSuccessMessage($this->createMessageTransfer($message, $data));
    }

    /**
     * @param string $message
     * @param array $data
     *
     * @return $this
     */
    protected function addInfoMessage($message, array $data = [])
    {
        $this->messengerFacade->addInfoMessage($this->createMessageTransfer($message, $data));

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
        $this->messengerFacade->addErrorMessage($this->createMessageTransfer($message, $data));

        return $this;
    }

    /**
     * @param string $message
     * @param array $data
     *
     * @return MessageTransfer
     */
    private function createMessageTransfer($message, array $data = [])
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($message);
        $messageTransfer->setParameters($data);

        return $messageTransfer;
    }

    /**
     * @return void
     */
    protected function clearBreadcrumbs()
    {
        $this->getTwig()->addGlobal('breadcrumbs', []);
    }

    /**
     * @throws \LogicException
     *
     * @return \Twig_Environment
     */
    private function getTwig()
    {
        $twig = $this->getApplication()['twig'];
        if ($twig === null) {
            throw new \LogicException('Twig environment not set up.');
        }

        return $twig;
    }

    /**
     * @return Application
     */
    protected function getApplication()
    {
        return $this->application;
    }

    /**
     * @param string $label
     * @param string $uri
     *
     * @return void
     */
    protected function addBreadcrumb($label, $uri)
    {
        $twig = $this->getTwig();
        $globals = $twig->getGlobals();
        $breadcrumbs = $globals['breadcrumbs'];

        if ($breadcrumbs === null) {
            $breadcrumbs = [];
        }

        $breadcrumbs[] = [
            'label' => $label,
            'uri' => $uri,
        ];

        $twig->addGlobal('breadcrumbs', $breadcrumbs);
    }

    /**
     * @param string $uri
     *
     * @return void
     */
    protected function setMenuHighlight($uri)
    {
        $this->getTwig()->addGlobal('menu_highlight', $uri);
    }

}
