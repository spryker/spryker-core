<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Application\Business;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Yves\YvesConfig;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\RouterInterface;

abstract class Bootstrap
{

    /**
     * @var Application
     */
    protected $application;

    /**
     * @param Application|null $application
     */
    public function __construct(Application $application = null)
    {
        if ($application) {
            $this->application = $application;
        }
    }

    /**
     * @return Application
     */
    public function boot()
    {
        $app = $this->getBaseApplication();

        if (\SprykerFeature_Shared_Library_Environment::isNotProduction()) {
            $app['debug'] = true;
        }

        $this->optimizeApp($app);

        $this->beforeBoot($app);
        $this->addProvidersToApp($app);
        $this->afterBoot($app);

        $this->addTwigExtensions($app);
        $this->addVariablesToTwig($app);
        $this->addProtocolCheck($app);

        return $app;
    }

    /**
     * @param Application $app
     */
    protected function addProvidersToApp(Application $app)
    {
        foreach ($this->getServiceProviders($app) as $provider) {
            $app->register($provider);
        }

        foreach ($this->getRouters($app) as $router) {
            $app->addRouter($router);
        }
    }

    /**
     * @param Application $app
     */
    protected function beforeBoot(Application $app)
    {
    }

    /**
     * @param Application $app
     */
    protected function afterBoot(Application $app)
    {
    }

    /**
     * @param Application $app
     *
     * @return \Twig_Extension[]
     */
    protected function getTwigExtensions(Application $app)
    {
        return [];
    }

    /**
     * @param Application $app
     *
     * @return array
     */
    protected function globalTemplateVariables(Application $app)
    {
        return [];
    }

    /**
     * @param \Pimple $app
     */
    protected function optimizeApp(\Pimple $app)
    {
        // We use the controller resolver from symfony as
        // we do not need the feature from the silex one
        $app['resolver'] = $app->share(function () use ($app) {
            return new ControllerResolver($app['logger']);
        });
    }

    /**
     * @param Application $app
     *
     * @return ServiceProviderInterface[]
     */
    abstract protected function getServiceProviders(Application $app);

    /**
     * @param Application $app
     *
     * @return RouterInterface[]
     */
    abstract protected function getRouters(Application $app);

    /**
     * @param \Pimple $app
     */
    private function addVariablesToTwig(\Pimple $app)
    {
        $app['twig.global.variables'] = $app->share(
            $app->extend('twig.global.variables', function (array $variables) use ($app) {
                return array_merge($variables, $this->globalTemplateVariables($app));
            })
        );
    }

    /**
     * @param \Pimple $app
     */
    private function addTwigExtensions(\Pimple $app)
    {
        $extensionsCallback = [$this, 'getTwigExtensions'];
        $app['twig'] = $app->share(
            $app->extend('twig', function (\Twig_Environment $twig) use ($extensionsCallback, $app) {
                foreach (call_user_func($extensionsCallback, $app) as $extension) {
                    $twig->addExtension($extension);
                }

                return $twig;
            })
        );
    }

    /**
     * @param Application$app
     */
    private function addProtocolCheck(Application $app)
    {
        if (Config::get(YvesConfig::YVES_SSL_ENABLED) && Config::get(YvesConfig::YVES_COMPLETE_SSL_ENABLED)) {
            $app->before(
                function (Request $request) {
                    if (!$request->isSecure()
                        && !in_array($request->getPathInfo(), Config::get(YvesConfig::YVES_SSL_EXCLUDED))
                    ) {
                        $fakeRequest = clone $request;
                        $fakeRequest->server->set('HTTPS', true);

                        return new RedirectResponse($fakeRequest->getUri(), 301);
                    }
                },
                255
            );
        }
    }

    /**
     * @return Application
     */
    abstract protected function getBaseApplication();

}
