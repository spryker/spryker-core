<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Communication\Plugin;

use SprykerFeature\Shared\Application\Communication\ControllerServiceBuilder;
use SprykerEngine\Yves\Kernel\Communication\BundleControllerAction;
use SprykerEngine\Yves\Kernel\Communication\Controller\BundleControllerActionRouteNameResolver;
use SprykerEngine\Yves\Kernel\Communication\ControllerLocator;
use Silex\Application;
use Silex\Controller;
use Silex\ControllerCollection;
use SprykerEngine\Yves\Kernel\Locator;
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
     *     true to force ssl
     *     false to force http
     *     null to not force anything (both https or http allowed)
     *
     * @param bool|null $sslEnabled
     */
    public function __construct($sslEnabled = null)
    {
        $this->sslEnabled = $sslEnabled;
    }

    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $this->app = $app;
        $this->controllerCollection = $app['controllers_factory'];
        $this->defineControllers($app);

        return $this->controllerCollection;
    }

    /**
     * {@inheritdoc}
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
        $controllerResolver = new ControllerLocator($bundleControllerAction);
        $routeResolver = new BundleControllerActionRouteNameResolver($bundleControllerAction);

        $service = (new ControllerServiceBuilder())->createServiceForController(
            $this->app,
            Locator::getInstance(),
            $bundleControllerAction,
            $controllerResolver,
            $routeResolver
        );

        $controller = $this->controllerCollection
            ->match($path, $service)
            ->bind($name);

        if (true === $this->sslEnabled) {
            $controller->requireHttps();
        } elseif (false === $this->sslEnabled) {
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
     */
    abstract protected function defineControllers(Application $app);

    /**
     * @param Controller $controller
     */
    private function addJsonParsing(Controller $controller)
    {
        $controller->before(function (Request $request) {
            $isJson = (0 === strpos($request->headers->get('Content-Type'), 'application/json'));
            if ($isJson) {
                $data = json_decode($request->getContent(), true);
                $request->request->replace(is_array($data) ? $data : []);
            }
        });
    }

}
