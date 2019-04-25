<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Application\Plugin\Provider;

use Silex\Application;
use Silex\Controller;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Application\Communication\ControllerServiceBuilder;
use Spryker\Shared\Config\Config;
use Spryker\Yves\Kernel\BundleControllerAction;
use Spryker\Yves\Kernel\ClassResolver\Controller\ControllerResolver;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated TODO
 */
abstract class YvesControllerProvider implements ControllerProviderInterface
{
    /**
     * @var \Silex\ControllerCollection
     */
    protected $controllerCollection;

    /**
     * @var \Silex\Application
     */
    protected $app;

    /**
     * @var bool
     */
    protected $sslEnabled;

    /**
     * Set the sslEnabledFlag to
     * true to force ssl
     * false to force http
     * null to not force anything (both https or http allowed)
     *
     * @param bool|null $sslEnabled
     */
    public function __construct($sslEnabled = null)
    {
        $this->sslEnabled = $sslEnabled;
    }

    /**
     * @param \Silex\Application $app
     *
     * @return \Silex\ControllerCollection
     */
    public function connect(Application $app)
    {
        $this->app = $app;
        $this->controllerCollection = $app['controllers_factory'];
        $this->defineControllers($app);

        return $this->controllerCollection;
    }

    /**
     * @return string
     */
    public function getUrlPrefix()
    {
        return '';
    }

    /**
     * @deprecated Not in use anymore. Will be removed with next major.
     *
     * @return mixed
     */
    public function getTransferSession()
    {
        return $this->app->getTransferSession();
    }

    /**
     * @return bool
     */
    protected function isSslEnabled()
    {
        return $this->sslEnabled;
    }

    /**
     * @param string $path
     * @param string $name
     * @param string $bundle
     * @param string $controllerName
     * @param string $actionName
     * @param bool $parseJsonBody
     *
     * @return \Silex\Controller
     */
    protected function createController(
        $path,
        $name,
        $bundle,
        $controllerName,
        $actionName = 'index',
        $parseJsonBody = false
    ) {
        $service = $this->getService($bundle, $controllerName, $actionName);
        $controller = $this->getController($path, $name, $service);
        if ($this->sslEnabled === true && !$this->isSslExcluded($name)) {
            $controller->requireHttps();
        } elseif ($this->sslEnabled === false) {
            $controller->requireHttp();
        }
        if ($parseJsonBody) {
            $this->addJsonParsing($controller);
        }

        return $controller;
    }

    /**
     * @param string $path
     * @param string $name
     * @param string $redirectPath
     * @param int $status
     *
     * @return \Silex\Controller
     */
    protected function createRedirectController($path, $name, $redirectPath, $status = 302)
    {
        $controller = $this->controllerCollection
            ->match($path, function () use ($redirectPath, $status) {
                return new RedirectResponse($redirectPath, $status);
            })
            ->bind($name);

        return $controller;
    }

    /**
     * @param string $path
     * @param string $name
     * @param string $bundle
     * @param string $controllerName
     * @param string $actionName
     *
     * @return \Silex\Controller
     */
    protected function createGetController($path, $name, $bundle, $controllerName, $actionName = 'index')
    {
        return $this->createController($path, $name, $bundle, $controllerName, $actionName)
            ->method('GET');
    }

    /**
     * @param string $path
     * @param string $name
     * @param string $bundle
     * @param string $controllerName
     * @param string $actionName
     * @param bool $parseJsonBody
     *
     * @return \Silex\Controller
     */
    protected function createPostController($path, $name, $bundle, $controllerName, $actionName = 'index', $parseJsonBody = false)
    {
        return $this->createController($path, $name, $bundle, $controllerName, $actionName, $parseJsonBody)
            ->method('POST');
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    abstract protected function defineControllers(Application $app);

    /**
     * @param \Silex\Controller $controller
     *
     * @return void
     */
    private function addJsonParsing(Controller $controller)
    {
        $controller->before(function (Request $request) {
            $isJson = (strpos($request->headers->get('Content-Type'), 'application/json') === 0);
            if ($isJson) {
                $data = json_decode($request->getContent(), true);
                $request->request->replace(is_array($data) ? $data : []);
            }
        });
    }

    /**
     * @param string $routeName
     *
     * @return bool
     */
    protected function isSslExcluded($routeName)
    {
        $excludedUrls = $this->getExcludedUrls();

        return !empty($excludedUrls[$routeName]);
    }

    /**
     * @return array
     */
    protected function getExcludedUrls()
    {
        return Config::get(ApplicationConstants::YVES_SSL_EXCLUDED, []);
    }

    /**
     * @param string $bundle
     * @param string $controllerName
     * @param string $actionName
     *
     * @return string
     */
    protected function getService($bundle, $controllerName, $actionName)
    {
        $bundleControllerAction = new BundleControllerAction($bundle, $controllerName, $actionName);
        $controllerResolver = new ControllerResolver();
        $routeResolver = new BundleControllerActionRouteNameResolver($bundleControllerAction);

        $service = (new ControllerServiceBuilder())->createServiceForController($this->app, $bundleControllerAction, $controllerResolver, $routeResolver);

        return $service;
    }

    /**
     * @param string $path
     * @param string $name
     * @param string $service
     *
     * @return \Silex\Controller
     */
    protected function getController($path, $name, $service)
    {
        $controller = $this->controllerCollection
            ->match($path, $service)
            ->bind($name);

        return $controller;
    }
}
