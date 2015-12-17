<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Application\Controller;

use Generated\Yves\Ide\AutoCompletion;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\Gui\Form\AbstractForm;
use Spryker\Yves\Application\Application;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Client\Kernel\ClassResolver\Client\ClientNotFoundException;
use Spryker\Client\Kernel\ClassResolver\Client\ClientResolver;
use Spryker\Yves\Kernel\ClassResolver\Factory\FactoryNotFoundException;
use Spryker\Yves\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Yves\Kernel\Locator;
use Spryker\Yves\Library\Session\TransferSession;
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

    /**
     * @var Application
     */
    private $application;

    /**
     * @var AbstractFactory
     */
    private $factory;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var AbstractClient
     */
    private $client;

    /**
     * @param Application $application
     *
     * @return $this
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * @return FlashBagInterface
     */
    protected function getFlashBag()
    {
        if ($this->flashBag === null) {
            $this->flashBag = $this->getApplication()['request']->getSession()->getFlashBag();
        }

        return $this->flashBag;
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
        return $this->application;
    }

    /**
     * @return string
     */
    protected function getLocale()
    {
        return $this->getApplication()['locale'];
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
     *
     * @return void
     */
    protected function addMessagesFromZedResponse($transferResponse)
    {
    }

    /**
     * @param $message
     *
     * @throws \ErrorException
     *
     * @return self
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
     * @return self
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
     * @return self
     */
    protected function addErrorMessage($message)
    {
        $this->addToFlashBag(self::FLASH_MESSAGES_ERROR, $message);

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return self
     */
    protected function addToFlashBag($key, $value)
    {
        $this->getFlashBag()->add($key, $value);

        return $this;
    }

    /**
     * @param string $type
     * @param null $data
     * @param array $options
     *
     * @return FormInterface
     *
     * @deprecated Use buildForm() instead.
     */
    protected function createForm($type = 'form', $data = null, array $options = [])
    {
        return $this->getApplication()->createForm($type, $data, $options);
    }

    /**
     * @param AbstractForm $form
     * @param array $options
     *
     * @return FormInterface
     */
    protected function buildForm(AbstractForm $form, array $options = [])
    {
        return $this->getApplication()->buildForm($form, $options);
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
        return $this->getApplication()['security.last_error']($request);
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
        return $this->getApplication()->render($viewPath, $parameters);
    }

    /**
     * @return AutoCompletion
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @return AbstractClient
     */
    protected function getClient()
    {
        if ($this->client === null) {
            $this->client = $this->resolveClient();
        }

        return $this->client;
    }

    /**
     * @throws ClientNotFoundException
     *
     * @return AbstractClient
     */
    private function resolveClient()
    {
        return $this->getClientResolver()->resolve($this);
    }

    /**
     * @return ClientResolver
     */
    private function getClientResolver()
    {
        return new ClientResolver();
    }

    /**
     * @return AbstractFactory
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @throws FactoryNotFoundException
     *
     * @return AbstractFactory
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

}
