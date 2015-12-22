<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Application\Plugin\Provider;

use Spryker\Shared\Application\Communication\ControllerServiceBuilder;
use Spryker\Yves\Kernel\BundleControllerAction;
use Spryker\Yves\Kernel\ClassResolver\Controller\ControllerResolver;
use Spryker\Yves\Kernel\Controller\BundleControllerActionRouteNameResolver;
use Silex\Application;
use Silex\Controller;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class YvesControllerProvider implements ControllerProviderInterface
{

    /**
     * @var ControllerCollection
     */
    private $controllerCollection;

    /**
     * @var Application
     */
    private $app;

    /**
     * @var bool
     */
    private $sslEnabled;

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
     * @param Application $app
     *
     * @return mixed|ControllerCollection
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
     * @return Controller
     */
    protected function createController(
        $path,
        $name,
        $bundle,
        $controllerName,
        $actionName = 'index',
        $parseJsonBody = false
    ) {
        $bundleControllerAction = new BundleControllerAction($bundle, $controllerName, $actionName);
        $controllerResolver = new ControllerResolver($bundleControllerAction);
        $routeResolver = new BundleControllerActionRouteNameResolver($bundleControllerAction);

        $service = (new ControllerServiceBuilder())->createServiceForController(
            $this->app,
            $bundleControllerAction,
            $controllerResolver,
            $routeResolver
        );

        $controller = $this->controllerCollection
            ->match($path, $service)
            ->bind($name);

        if ($this->sslEnabled === true) {
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
     * @return Controller
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
     * @return Controller
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
     * @return Controller
     */
    protected function createPostController($path, $name, $bundle, $controllerName, $actionName = 'index', $parseJsonBody = false)
    {
        return $this->createController($path, $name, $bundle, $controllerName, $actionName, $parseJsonBody)
            ->method('POST');
    }

    /**
     * @param Application $app
     *
     * @return void
     */
    abstract protected function defineControllers(Application $app);

    /**
     * @param Controller $controller
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

}
