<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Zed\Application\Business\Model\Twig\RouteResolver;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Gui\Communication\Form\Type\Extension\NoValidateTypeExtension;
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

        $this->provideFormTypeExtension();
        $this->provideFormTypeTemplates();

        $app['twig.loader.zed'] = $app->share(function () {
            $namespace = Config::get(SystemConfig::PROJECT_NAMESPACE);

            $storeName = \SprykerEngine\Shared\Kernel\Store::getInstance()->getStoreName();
            return new Filesystem(
                [
                    APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s' . $storeName . '/Presentation/',
                    APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s/Presentation/',

                    APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/%1$s/src/SprykerFeature/Zed/%1$s' . $storeName . '/Presentation/',
                    APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/%1$s/src/SprykerFeature/Zed/%1$s/Presentation/',
                ]
            );
        });

        $app['twig.loader'] = $app->share(function ($app) {
            return new \Twig_Loader_Chain(
                [
                    $app['twig.loader.zed'],
                    $app['twig.loader.filesystem'],
                ]
            );
        });

        if (false === \SprykerFeature_Shared_Library_Environment::isDevelopment()) {
            $app['twig.options'] = [
                'cache' => \SprykerFeature_Shared_Library_Data::getLocalStoreSpecificPath('cache/twig'),
            ];
        }

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
        $app['dispatcher']->addListener(KernelEvents::VIEW, [$this, 'onKernelView']);
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
            return;
        }

        if (isset($parameters['alternativeRoute'])) {
            $route = (string) $parameters['alternativeRoute'];
        } else {
            $route = (new RouteResolver())
                ->buildRouteFromControllerServiceName($controller)
            ;
        }

        return $this->app->render('@' . $route . '.twig', $parameters);
    }

    /**
     * @return void
     */
    protected  function provideFormTypeExtension()
    {
        $this->app['form.type.extensions'] = $this->app->share(function () {
            return array(
                new NoValidateTypeExtension()
            );
        });

    }

    /**
     * @return void
     */
    protected function provideFormTypeTemplates()
    {
        $path = APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/Gui/src/SprykerFeature/Zed/Gui/Presentation/Form/Type';

        $this->app['twig.loader.filesystem']->addPath(
            $path
        );

        $files = new \FilesystemIterator($path, \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::KEY_AS_PATHNAME);

        $typeTemplates = array();
        foreach ($files as $file) {
            $typeTemplates[] = $file->getFilename();
        }

        $this->app['twig.form.templates'] = array_merge([
            'bootstrap_3_layout.html.twig',
        ], $typeTemplates);
    }
}
