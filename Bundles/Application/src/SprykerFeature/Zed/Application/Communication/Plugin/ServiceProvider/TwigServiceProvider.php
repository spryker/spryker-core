<?php

namespace SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Zed\Application\Business\Model\Twig\RouteResolver;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Library\Twig\Loader\Filesystem;
use Silex\Application;
use Silex\Provider\TwigServiceProvider as SilexTwigServiceProvider;
use SprykerEngine\Yves\Application\Business\Application as SprykerApplication;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class TwigServiceProvider extends SilexTwigServiceProvider
{
    /**
     * @var SprykerApplication
     */
    private $app;

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $this->app = $app;

        parent::register($app);

        $app['twig.loader.zed'] = $app->share(function () {
            $namespace = Config::get(SystemConfig::PROJECT_NAMESPACE);

            $storeName = \SprykerEngine\Shared\Kernel\Store::getInstance()->getStoreName();

            return new Filesystem(
                [
                    APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s' . $storeName . '/Presentation/Layout/',
                    APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s' . $storeName . '/Presentation/',
                    APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s' . $storeName . '/Presentation/Widget/',
                    APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s/Presentation/Layout/',
                    APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s/Presentation/',
                    APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s/Presentation/Widget/',

                    APPLICATION_VENDOR_DIR . '/spryker/*/src/SprykerFeature/Zed/%s' . $storeName . '/Presentation/Layout/',
                    APPLICATION_VENDOR_DIR . '/spryker/*/src/SprykerFeature/Zed/%s' . $storeName . '/Presentation/',
                    APPLICATION_VENDOR_DIR . '/spryker/*/src/SprykerFeature/Zed/%s' . $storeName . '/Presentation/Widget/',
                    APPLICATION_VENDOR_DIR . '/spryker/*/src/SprykerFeature/Zed/%s/Presentation/Layout/',
                    APPLICATION_VENDOR_DIR . '/spryker/*/src/SprykerFeature/Zed/%s/Presentation/',
                    APPLICATION_VENDOR_DIR . '/spryker/*/src/SprykerFeature/Zed/%s/Presentation/Widget/',

                    APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/SprykerFeature/Zed/%s' . $storeName . '/Presentation/Layout/',
                    APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/SprykerFeature/Zed/%s' . $storeName . '/Presentation/',
                    APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/SprykerFeature/Zed/%s' . $storeName . '/Presentation/Widget/',
                    APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/SprykerFeature/Zed/%s/Presentation/Layout/',
                    APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/SprykerFeature/Zed/%s/Presentation/',
                    APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/SprykerFeature/Zed/%s/Presentation/Widget/'
                ]
            );
        });

        $app['twig.loader'] = $app->share(function ($app) {
            return new \Twig_Loader_Chain(
                [
                    $app['twig.loader.zed'],
                    $app['twig.loader.filesystem']
                ]
            );
        });

        $app['twig.options'] = array(
            'cache' => \SprykerFeature_Shared_Library_Data::getLocalStoreSpecificPath('cache/twig')
        );

        $app['twig.global.variables'] = $app->share(function () {
             return [];
        });
        $app['twig'] = $app->share(
            $app->extend(
                'twig',
                function (\Twig_Environment $twig) use ($app) {
                    foreach ($app['twig.global.variables'] as $name => $value) {
                        $twig->addGlobal($name, $value);
                    }

                    return $twig;
                }
            )
        );
    }

    /**
     * Handles string responses.
     *
     * @param GetResponseForControllerResultEvent $event The event to handle
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $response = $event->getControllerResult();

        if (empty($response) || is_array($response)) {
            $response = $this->render((array) $response);
            if ($response instanceof Response) {
                $event->setResponse($response);
            }
        }
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $app['dispatcher']->addListener(KernelEvents::VIEW, array($this, 'onKernelView'));
    }

    /**
     * Renders the template for the current controller/action
     *
     * @param array $parameters
     *
     * @return Response
     */
    protected function render(array $parameters = [])
    {
        $controller = $this->app['request']->attributes->get('_controller');

        if (!is_string($controller) || empty($controller)) {
            return null;
        }

        if (isset($parameters['alternativeRoute'])) {
            $route = (string)$parameters['alternativeRoute'];
        } else {
            $route = (new RouteResolver())
                ->buildRouteFromControllerServiceName($controller)
            ;
        }

        return $this->app->render('@' . $route . '.twig', $parameters);
    }
}
