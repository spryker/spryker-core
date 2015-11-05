<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Communication\Controller;

use Generated\Yves\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;
use SprykerEngine\Yves\Application\Communication\Application;
use SprykerEngine\Yves\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\AbstractDependencyContainer;
use SprykerFeature\Yves\Library\Session\TransferSession;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

abstract class AbstractController
{

    const FLASH_MESSAGES_SUCCESS = 'flash.messages.success';
    const FLASH_MESSAGES_ERROR= 'flash.messages.error';
    const FLASH_MESSAGES_INFO = 'flash.messages.info';

    const DEPENDENCY_CONTAINER = 'DependencyContainer';

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var Application
     */
    private $app;

    /**
     * @var AbstractDependencyContainer
     */
    private $dependencyContainer;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @param Application $app
     * @param Factory $factory
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(Application $app, Factory $factory, LocatorLocatorInterface $locator)
    {
        $this->app = $app;
        $this->locator = $locator;
        $this->factory = $factory;
        $this->flashBag = $app['request']->getSession()->getFlashBag();

        if ($factory->exists(self::DEPENDENCY_CONTAINER)) {
            $this->dependencyContainer = $factory->create(self::DEPENDENCY_CONTAINER, $factory, $locator);
        }
    }

    /**
     * @return Factory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param int $code
     *
     * @return RedirectResponse
     */
    protected function redirectResponseInternal($path, $parameters = [], $code = 302)
    {
        return new RedirectResponse($this->getApplication()->path($path, $parameters), $code);
    }

    /**
     * @return Application
     */
    protected function getApplication()
    {
        return $this->app;
    }

    /**
     * @return \ArrayObject
     */
    protected function getCookieBag()
    {
        return $this->app->getCookieBag();
    }

    /**
     * @return TransferSession
     */
    protected function getTransferSession()
    {
        return $this->app->getTransferSession();
    }

    /**
     * @return string
     */
    protected function getLocale()
    {
        return $this->app['locale'];
    }

    /**
     * @return mixed
     */
    protected function getTranslator()
    {
        return $this->getApplication()['translator'];
    }

    /**
     * @param string $absoluteUrl
     * @param int $code
     *
     * @return RedirectResponse
     */
    protected function redirectResponseExternal($absoluteUrl, $code = 302)
    {
        return new RedirectResponse($absoluteUrl, $code);
    }

    /**
     * @param null $data
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
     * @param array $data
     *
     * @return array
     */
    protected function viewResponse(array $data = [])
    {
        return $data;
    }

    /**
     * @param $transferResponse
     */
    protected function addMessagesFromZedResponse($transferResponse)
    {
        //$this->getMessenger()->addMessagesFromResponse($transferResponse);
    }

    /**
     * @param $message
     *
     * @throws \ErrorException
     *
     * @return $this
     */
    protected function addSuccessMessage($message)
    {
        $this->addToFlashBag(self::FLASH_MESSAGES_SUCCESS, $message);

        return $this;
    }

    /**
     * @param string $message
     *
     * @throws \ErrorException
     *
     * @return $this
     */
    protected function addInfoMessage($message)
    {
        $this->addToFlashBag(self::FLASH_MESSAGES_INFO, $message);

        return $this;
    }

    /**
     * @param string $message
     *
     * @throws \ErrorException
     *
     * @return $this
     */
    protected function addErrorMessage($message)
    {
        $this->addToFlashBag(self::FLASH_MESSAGES_ERROR, $message);

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     */
    protected function addToFlashBag($key, $value)
    {
        $this->flashBag->add($key, $value);
    }

    /**
     * @param string $type
     * @param null $data
     * @param array $options
     *
     * @return FormInterface
     *
     * @deprecated will be removed when we have our ComFactory
     */
    protected function createForm($type = 'form', $data = null, array $options = [])
    {
        return $this->getApplication()->createForm($type, $data, $options);
    }

    /**
     * @TODO rethink
     *
     * @param string $role
     *
     * @return mixed
     */
    protected function isGranted($role)
    {
        $security = $this->getApplication()['security'];
        if ($security) {
            return $security->isGranted($role);
        }

        throw new \LogicException('Security is not enabled!');
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    protected function getSecurityError(Request $request)
    {
        return $this->app['security.last_error']($request);
    }

    /**
     * @return bool
     */
    protected function hasUser()
    {
        $securityContext = $this->getSecurityContext();
        $token = $securityContext->getToken();

        return $token === null;
    }

    /**
     * @throws \LogicException
     *
     * @return mixed
     */
    protected function getSecurityContext()
    {
        $securityContext = $this->getApplication()['security'];
        if ($securityContext === null) {
            throw new \LogicException('Security is not enabled!');
        }

        return $securityContext;
    }

    /**
     * @return string
     */
    protected function getUsername()
    {
        $user = $this->getUser();
        if (is_string($user)) {
            return $user;
        }

        return $user->getUsername();
    }

    /**
     * @return mixed
     */
    protected function getUser()
    {
        $securityContext = $this->getSecurityContext();
        $token = $securityContext->getToken();
        if ($token === null) {
            throw new \LogicException('No logged in user found.');
        }

        return $token->getUser();
    }

    /**
     * @param string $viewPath
     * @param array $parameters
     *
     * @return Response
     */
    protected function renderView($viewPath, array $parameters = [])
    {
        return $this->app->render($viewPath, $parameters);
    }

    /**
     * @return AutoCompletion
     */
    protected function getLocator()
    {
        return $this->locator;
    }

    /**
     * @return AbstractDependencyContainer
     */
    protected function getDependencyContainer()
    {
        return $this->dependencyContainer;
    }

    /**
     * @return MessengerInterface
     */
    private function getMessenger()
    {
        return;
        //return $this->getTwig()->getExtension('TwigMessengerPlugin')->getMessenger();
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

}
