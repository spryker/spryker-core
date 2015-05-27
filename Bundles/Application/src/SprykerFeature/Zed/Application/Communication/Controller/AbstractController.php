<?php

namespace SprykerFeature\Zed\Application\Communication\Controller;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use Silex\Application;
use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;
use SprykerEngine\Zed\Messenger\Communication\Presenter\ZedPresenter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use LogicException;
use Twig_Environment;

abstract class AbstractController
{

    /**
     * @var Application
     */
    private $application;

    /**
     * @var AutoCompletion
     */
    private $locator;

    /**
     * @var AbstractDependencyContainer
     */
    private $dependencyContainer;

    /**
     * @var MessengerInterface
     */
    private $messenger;

    /**
     * @param Application $application
     * @param Factory $factory
     * @param Locator $locator
     */
    public function __construct(Application $application, Factory $factory, Locator $locator)
    {
        $this->application = $application;
        $this->locator = $locator;

        $this->messenger = $this->locator->messenger()->facade();

        $this->locator->messenger()->facade()->createZedPresenter(
            $this->messenger,
            $this->locator->translation()->facade(),
            $this->locator->locale()->facade()->getCurrentLocale(),
            $this->getTwig()
        );

        if ($factory->exists('DependencyContainer')) {
            $this->dependencyContainer = $factory->create('DependencyContainer', $factory, $locator);
        }
    }

    /**
     * @return MessengerInterface
     */
    private function getMessenger()
    {
        return $this->messenger;
    }

    /**
     * @param string $url
     * @param int $status
     * @param array $headers
     *
     * @return RedirectResponse
     */
    protected function redirectResponse($url, $status = 302, $headers = array())
    {
        return new RedirectResponse($url, $status, $headers);
    }

    /**
     * @param null $data
     * @param int $status
     * @param array $headers
     * @return JsonResponse
     */
    protected function jsonResponse($data = null, $status = 200, $headers = array())
    {
        return new JsonResponse($data, $status, $headers);
    }

    /**
     * @param null $callback
     * @param int $status
     * @param array $headers
     * @return StreamedResponse
     */
    protected function streamedResponse($callback = null, $status = 200, $headers = array())
    {
        $streamedResponse = new StreamedResponse($callback, $status, $headers);
        $streamedResponse->send();

        return $streamedResponse;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function viewResponse(array $data = [])
    {
        return $data;
    }

    /**
     * @param string $message
     *
     * @return $this
     * @throws \ErrorException
     */
    protected function addMessageSuccess($message)
    {
        $this->getMessenger()->success($message);

        return $this;
    }

    /**
     * @param string $message
     *
     * @return $this
     * @throws \Exception
     */
    protected function addMessageWarning($message)
    {
        $this->getMessenger()->warning($message);

        return $this;
    }

    /**
     * @param string $message
     *
     * @return $this
     * @throws \ErrorException
     */
    protected function addMessageError($message)
    {
        $this->getMessenger()->error($message);

        return $this;
    }

    /**
     * @param string $type
     * @param null $data
     * @param array $options
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    protected function createForm($type = 'form', $data = null, array $options = array())
    {
        /* @var $formFactory \Symfony\Component\Form\FormFactory */
        $formFactory = $this->application['form.factory'];

        return $formFactory->create($type, $data, $options);
    }

    /**
     * @return AutoCompletion
     */
    public function getLocator()
    {
        return $this->locator;
    }

    /**
     * @return AbstractDependencyContainer
     */
    public function getDependencyContainer()
    {
        return $this->dependencyContainer;
    }

    /**
     * @return \Pimple
     */
    protected function getApplication()
    {
        return $this->application;
    }

    /**
     * @return Twig_Environment
     * @throws LogicException
     */
    private function getTwig()
    {
        $twig = $this->getApplication()['twig'];
        if ($twig === null) {
            throw new LogicException('Twig environment not set up.');
        }

        return $twig;
    }

    /**
     * @return void
     */
    protected function clearBreadcrumbs()
    {
        $this->getTwig()->addGlobal('breadcrumbs', []);
    }

    /**
     * @param string $label
     * @param string $uri
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
     */
    protected function setMenuHighlight($uri)
    {
        $this->getTwig()->addGlobal('menu_highlight', $uri);
    }
}
