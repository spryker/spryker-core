<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Controller;

use Silex\Application;
use Spryker\Client\Kernel\ClassResolver\Client\ClientResolver;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Yves\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Yves\Kernel\Dependency\Messenger\KernelToMessengerBridge;
use Spryker\Yves\Kernel\Dependency\Messenger\NullMessenger;
use Spryker\Yves\Kernel\Exception\ForbiddenExternalRedirectException;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class AbstractController
{
    /**
     * @var \Spryker\Yves\Kernel\Application
     */
    private $application;

    /**
     * @var \Spryker\Yves\Kernel\AbstractFactory
     */
    private $factory;

    /**
     * @var \Spryker\Client\Kernel\AbstractClient
     */
    private $client;

    /**
     * @return void
     */
    public function initialize()
    {
    }

    /**
     * @param \Spryker\Yves\Kernel\Application $application
     *
     * @return $this
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param int $code
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectResponseInternal($path, $parameters = [], $code = 302)
    {
        return new RedirectResponse($this->getApplication()->path($path, $parameters), $code);
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
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
     * @param $absoluteUrl
     * @param int $code
     *
     * @throws \Spryker\Yves\Kernel\Exception\ForbiddenExternalRedirectException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectResponseExternal($absoluteUrl, $code = 302)
    {
        if (strpos($absoluteUrl, '/') != 0 && !$this->isUrlWhitelisted($absoluteUrl)) {
            throw new ForbiddenExternalRedirectException("The url is not in the whitelist: $absoluteUrl");
        }

        return new RedirectResponse($absoluteUrl, $code);
    }

    /**
     * @param mixed|null $data
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
     * @param array $data
     *
     * @return array
     */
    protected function viewResponse(array $data = [])
    {
        return $data;
    }

    /**
     * @param array $data
     * @param \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface[] $widgetPlugins
     * @param string|null $template
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    protected function view(array $data = [], array $widgetPlugins = [], $template = null)
    {
        return new View($data, $widgetPlugins, $template);
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    protected function addSuccessMessage($message)
    {
        $this->getMessenger()->addSuccessMessage($message);

        return $this;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    protected function addInfoMessage($message)
    {
        $this->getMessenger()->addInfoMessage($message);

        return $this;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    protected function addErrorMessage($message)
    {
        $this->getMessenger()->addErrorMessage($message);

        return $this;
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    protected function getClient()
    {
        if ($this->client === null) {
            $this->client = $this->resolveClient();
        }

        return $this->client;
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    private function resolveClient()
    {
        return $this->getClientResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Client\Kernel\ClassResolver\Client\ClientResolver
     */
    private function getClientResolver()
    {
        return new ClientResolver();
    }

    /**
     * @return \Spryker\Yves\Kernel\AbstractFactory
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @return \Spryker\Yves\Kernel\AbstractFactory
     */
    private function resolveFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Yves\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }

    /**
     * @return \Spryker\Yves\Kernel\Dependency\Messenger\KernelToMessengerInterface
     */
    private function getMessenger()
    {
        $messenger = ($this->application->offsetExists('flash_messenger')) ? $this->application['flash_messenger'] : new NullMessenger();
        $applicationToMessengerBridge = new KernelToMessengerBridge($messenger);

        return $applicationToMessengerBridge;
    }

    /**
     * @param string $viewPath
     * @param array $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderView($viewPath, array $parameters = [])
    {
        return $this->getApplication()->render($viewPath, $parameters);
    }

    protected function isUrlWhitelisted(string $absoluteUrl)
    {
        $absoluteUrl = str_replace(['http://', 'https://'], '', $absoluteUrl);
        $whitelistUrls = Config::getInstance()->get(KernelConstants::URL_WHITELIST);

        foreach ($whitelistUrls as $whitelistUrl) {
            if (strpos($absoluteUrl, $whitelistUrl !== false)) {
                return true;
            }
        }

        return false;
    }
}
