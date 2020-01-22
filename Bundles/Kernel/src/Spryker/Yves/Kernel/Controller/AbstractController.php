<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Controller;

use Spryker\Client\Kernel\ClassResolver\Client\ClientResolver;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Yves\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Yves\Kernel\Dependency\Messenger\KernelToMessengerBridge;
use Spryker\Yves\Kernel\Dependency\Messenger\NullMessenger;
use Spryker\Yves\Kernel\Exception\ForbiddenExternalRedirectException;
use Spryker\Yves\Kernel\View\View;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class AbstractController
{
    protected const SERVICE_LOCALE = 'locale';
    protected const SERVICE_FLASH_MESSENGER = 'flash_messenger';

    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     */
    protected const SERVICE_ROUTER = 'routers';

    /**
     * @uses \Spryker\Yves\Twig\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     */
    protected const SERVICE_TWIG = 'twig';

    /**
     * @var \Spryker\Yves\Kernel\Application|\Spryker\Service\Container\ContainerInterface
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
     * @param \Spryker\Yves\Kernel\Application|\Spryker\Service\Container\ContainerInterface $application
     *
     * @return $this
     */
    public function setApplication($application)
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
        return new RedirectResponse($this->getRouter()->generate($path, $parameters), $code);
    }

    /**
     * @return \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    protected function getRouter(): ChainRouterInterface
    {
        return $this->getApplication()->get(static::SERVICE_ROUTER);
    }

    /**
     * @return \Spryker\Yves\Kernel\Application|\Spryker\Service\Container\ContainerInterface
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
        return $this->getApplication()->get(static::SERVICE_LOCALE);
    }

    /**
     * @see \Spryker\Shared\Kernel\KernelConstants::STRICT_DOMAIN_REDIRECT For strict redirection check status.
     * @see \Spryker\Shared\Kernel\KernelConstants::DOMAIN_WHITELIST For allowed list of external domains.
     *
     * @param string $absoluteUrl
     * @param int $code
     *
     * @throws \Spryker\Yves\Kernel\Exception\ForbiddenExternalRedirectException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectResponseExternal($absoluteUrl, $code = 302)
    {
        if (strpos($absoluteUrl, '/') !== 0 && !$this->isUrlDomainWhitelisted($absoluteUrl)) {
            throw new ForbiddenExternalRedirectException("This URL $absoluteUrl is not a part of a whitelisted domain");
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
     * @param string[] $widgetPlugins
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
        $messenger = ($this->getApplication()->has(static::SERVICE_FLASH_MESSENGER)) ? $this->getApplication()->get(static::SERVICE_FLASH_MESSENGER) : new NullMessenger();
        $applicationToMessengerBridge = new KernelToMessengerBridge($messenger);

        return $applicationToMessengerBridge;
    }

    /**
     * @param string $view
     * @param array $parameters
     * @param \Symfony\Component\HttpFoundation\Response|null $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderView($view, array $parameters = [], ?Response $response = null)
    {
        if ($response instanceof StreamedResponse) {
            $response->setCallback(function () use ($view, $parameters) {
                $this->getTwig()->display($view, $parameters);
            });
        } else {
            if ($response === null) {
                $response = new Response();
            }
            $response->setContent($this->getTwig()->render($view, $parameters));
        }

        return $response;
    }

    /**
     * @return \Twig\Environment
     */
    protected function getTwig()
    {
        return $this->getApplication()->get(static::SERVICE_TWIG);
    }

    /**
     * @param string $absoluteUrl
     *
     * @return bool
     */
    protected function isUrlDomainWhitelisted(string $absoluteUrl): bool
    {
        $whitelistedDomains = Config::getInstance()->get(KernelConstants::DOMAIN_WHITELIST, []);
        $isStrictDomainRedirect = Config::get(KernelConstants::STRICT_DOMAIN_REDIRECT, false);

        if (empty($whitelistedDomains) && !$isStrictDomainRedirect) {
            return true;
        }

        foreach ($whitelistedDomains as $whitelistedDomain) {
            if ($this->extractDomainFromUrl($absoluteUrl) === $whitelistedDomain) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $url
     *
     * @return string
     */
    protected function extractDomainFromUrl(string $url): string
    {
        /** @var string|false $urlDomain */
        $urlDomain = parse_url($url, PHP_URL_HOST);
        if ($urlDomain === false) {
            return '';
        }

        return $urlDomain;
    }
}
