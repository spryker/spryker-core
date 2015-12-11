<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Controller;

use Generated\Shared\Transfer\MessageTransfer;
use Silex\Application;
use Spryker\Zed\Messenger\Business\MessengerFacade;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\ClassResolver\DependencyContainer\DependencyContainerNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\DependencyContainer\DependencyContainerResolver;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Spryker\Zed\Kernel\Communication\DependencyContainer\DependencyContainerInterface;
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
     * @var AbstractCommunicationDependencyContainer
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
     * @return DependencyContainerInterface
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
        return $this->getDependencyContainerResolver()->resolve($this);
    }

    /**
     * @return DependencyContainerResolver
     */
    protected function getDependencyContainerResolver()
    {
        return new DependencyContainerResolver();
    }

    /**
     * @param AbstractFacade $facade
     *
     * @return self
     */
    public function setOwnFacade(AbstractFacade $facade)
    {
        $this->facade = $facade;

        return $this;
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
     * @return self
     */
    public function setOwnQueryContainer(AbstractQueryContainer $queryContainer)
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
     *
     * @return self
     */
    protected function addInfoMessage($message, array $data = [])
    {
        $this->messengerFacade->addInfoMessage($this->createMessageTransfer($message, $data));

        return $this;
    }

    /**
     * @param string $message
     *
     * @return self
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
