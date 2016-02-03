<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Application\Communication;

use Spryker\Shared\Application\Communication\Bootstrap\Extension\AfterBootExtensionInterface;
use Spryker\Shared\Application\Communication\Bootstrap\Extension\BeforeBootExtensionInterface;
use Spryker\Shared\Application\Communication\Bootstrap\Extension\GlobalTemplateVariableExtensionInterface;
use Spryker\Shared\Application\Communication\Bootstrap\Extension\RouterExtensionInterface;
use Spryker\Shared\Application\Communication\Bootstrap\Extension\ServiceProviderExtensionInterface;
use Spryker\Shared\Application\Communication\Bootstrap\Extension\TwigExtensionInterface;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config;
use Symfony\Component\HttpFoundation\Request;
use Spryker\Zed\Kernel\ControllerResolver\ZedFragmentControllerResolver;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Bootstrap
{

    /**
     * @var \Spryker\Shared\Application\Communication\Bootstrap\Extension\BeforeBootExtensionInterface[]
     */
    private $beforeBootExtensions = [];

    /**
     * @var \Spryker\Shared\Application\Communication\Bootstrap\Extension\AfterBootExtensionInterface[]
     */
    private $afterBootExtensions = [];

    /**
     * @var \Spryker\Shared\Application\Communication\Bootstrap\Extension\TwigExtensionInterface[]
     */
    private $twigExtensions = [];

    /**
     * @var \Spryker\Shared\Application\Communication\Bootstrap\Extension\GlobalTemplateVariableExtensionInterface[]
     */
    private $globalTemplateVariableExtensions = [];

    /**
     * @var \Spryker\Shared\Application\Communication\Bootstrap\Extension\ServiceProviderExtensionInterface[]
     */
    private $serviceProviderExtensions = [];

    /**
     * @var \Spryker\Shared\Application\Communication\Bootstrap\Extension\RouterExtensionInterface[]
     */
    private $routerExtensions = [];

    /**
     * @param \Spryker\Shared\Application\Communication\Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @return \Spryker\Shared\Application\Communication\Application
     */
    public function boot()
    {
        $this->application['debug'] = Config::get(ApplicationConstants::ENABLE_APPLICATION_DEBUG, false);

        $this->optimizeApp($this->application);

        $this->beforeBoot($this->application);
        $this->addProvidersToApp($this->application);
        $this->afterBoot($this->application);

        $this->addTwigExtensions($this->application);
        $this->addVariablesToTwig($this->application);
        $this->addProtocolCheck($this->application);

        return $this->application;
    }

    /**
     * @param \Spryker\Shared\Application\Communication\Application $application
     *
     * @return void
     */
    protected function addProvidersToApp(Application $application)
    {
        foreach ($this->getServiceProviders($application) as $provider) {
            $application->register($provider);
        }

        foreach ($this->getRouters($application) as $router) {
            $application->addRouter($router);
        }
    }

    /**
     * @param \Spryker\Shared\Application\Communication\Bootstrap\Extension\BeforeBootExtensionInterface $beforeBootExtension
     *
     * @return self
     */
    public function addBeforeBootExtension(BeforeBootExtensionInterface $beforeBootExtension)
    {
        $this->beforeBootExtensions[] = $beforeBootExtension;

        return $this;
    }

    /**
     * @param \Spryker\Shared\Application\Communication\Application $application
     *
     * @return void
     */
    protected function beforeBoot(Application $application)
    {
        foreach ($this->beforeBootExtensions as $beforeBootExtension) {
            $beforeBootExtension->beforeBoot($application);
        }
    }

    /**
     * @param \Spryker\Shared\Application\Communication\Bootstrap\Extension\AfterBootExtensionInterface $afterBootExtension
     *
     * @return self
     */
    public function addAfterBootExtension(AfterBootExtensionInterface $afterBootExtension)
    {
        $this->afterBootExtensions[] = $afterBootExtension;

        return $this;
    }

    /**
     * @param \Spryker\Shared\Application\Communication\Application $application
     *
     * @return void
     */
    protected function afterBoot(Application $application)
    {
        foreach ($this->afterBootExtensions as $afterBootExtension) {
            $afterBootExtension->afterBoot($application);
        }
    }

    /**
     * @param \Spryker\Shared\Application\Communication\Bootstrap\Extension\TwigExtensionInterface $twigExtension
     *
     * @return self
     */
    public function addTwigExtension(TwigExtensionInterface $twigExtension)
    {
        $this->twigExtensions[] = $twigExtension;

        return $this;
    }

    /**
     * @param \Spryker\Shared\Application\Communication\Application $application
     *
     * @return \Twig_Extension[]
     */
    protected function getTwigExtensions(Application $application)
    {
        $twigExtensions = [];

        foreach ($this->twigExtensions as $twigExtension) {
            $twigExtensions = array_merge($twigExtensions, $twigExtension->getTwigExtensions($application));
        }

        return $twigExtensions;
    }

    /**
     * @param \Spryker\Shared\Application\Communication\Bootstrap\Extension\GlobalTemplateVariableExtensionInterface $globalTemplateVariableExtension
     *
     * @return self
     */
    public function addGlobalTemplateVariableExtension(GlobalTemplateVariableExtensionInterface $globalTemplateVariableExtension)
    {
        $this->globalTemplateVariableExtensions[] = $globalTemplateVariableExtension;

        return $this;
    }

    /**
     * @param \Spryker\Shared\Application\Communication\Application $application
     *
     * @return array
     */
    protected function globalTemplateVariables(Application $application)
    {
        $globalTemplateVariables = [];

        foreach ($this->globalTemplateVariableExtensions as $globalTemplateVariableExtension) {
            $providedVariables = $globalTemplateVariableExtension->getGlobalTemplateVariables($application);
            $globalTemplateVariables = array_merge($globalTemplateVariables, $providedVariables);
        }

        return $globalTemplateVariables;
    }

    /**
     * @param \Spryker\Shared\Application\Communication\Application $application
     *
     * @return void
     */
    protected function optimizeApp(Application $application)
    {
        $application['resolver'] = $application->share(function () use ($application) {
            return new ZedFragmentControllerResolver($application, $application['logger']);
        });
    }

    /**
     * @param \Spryker\Shared\Application\Communication\Bootstrap\Extension\ServiceProviderExtensionInterface $serviceProviderExtension
     *
     * @return self
     */
    public function addServiceProviderExtension(ServiceProviderExtensionInterface $serviceProviderExtension)
    {
        $this->serviceProviderExtensions[] = $serviceProviderExtension;

        return $this;
    }

    /**
     * @param \Spryker\Shared\Application\Communication\Application $application
     *
     * @return \Silex\ServiceProviderInterface[]
     */
    protected function getServiceProviders(Application $application)
    {
        $serviceProvider = [];

        foreach ($this->serviceProviderExtensions as $serviceProviderExtension) {
            $providedExtensions = $serviceProviderExtension->getServiceProvider($application);
            $serviceProvider = $this->mergeServiceProvider($serviceProvider, $providedExtensions);
        }

        return $serviceProvider;
    }

    /**
     * @param array $givenServiceProvider
     * @param array $additionalServiceProvider
     *
     * @return array
     */
    private function mergeServiceProvider(array $givenServiceProvider, array $additionalServiceProvider)
    {
        $merged = array_merge($givenServiceProvider, $additionalServiceProvider);

        return $merged;
    }

    /**
     * @param \Spryker\Shared\Application\Communication\Bootstrap\Extension\RouterExtensionInterface $routerExtension
     *
     * @return self
     */
    public function addRouterExtension(RouterExtensionInterface $routerExtension)
    {
        $this->routerExtensions[] = $routerExtension;

        return $this;
    }

    /**
     * @param \Spryker\Shared\Application\Communication\Application $application
     *
     * @return \Symfony\Component\Routing\RouterInterface[]
     */
    protected function getRouters(Application $application)
    {
        $router = [];

        foreach ($this->routerExtensions as $routerExtension) {
            $providedExtensions = $routerExtension->getRouter($application);
            $router = $this->mergeRouter($router, $providedExtensions);
        }

        return $router;
    }

    /**
     * @param array $givenRouter
     * @param array $additionalRouter
     *
     * @return array
     */
    private function mergeRouter(array $givenRouter, array $additionalRouter)
    {
        $merged = array_merge($givenRouter, $additionalRouter);

        return $merged;
    }

    /**
     * @param \Pimple $application
     *
     * @return void
     */
    private function addVariablesToTwig(\Pimple $application)
    {
        $application['twig.global.variables'] = $application->share(
            $application->extend('twig.global.variables', function (array $variables) use ($application) {
                return array_merge($variables, $this->globalTemplateVariables($application));
            })
        );
    }

    /**
     * @param \Pimple $application
     *
     * @return void
     */
    private function addTwigExtensions(\Pimple $application)
    {
        $extensionsCallback = [$this, 'getTwigExtensions'];
        $application['twig'] = $application->share(
            $application->extend('twig', function (\Twig_Environment $twig) use ($extensionsCallback, $application) {
                foreach (call_user_func($extensionsCallback, $application) as $extension) {
                    $twig->addExtension($extension);
                }

                return $twig;
            })
        );
    }

    /**
     * @param \Spryker\Shared\Application\Communication\Application $application
     *
     * @throws \Exception
     *
     * @return void
     */
    private function addProtocolCheck(Application $application)
    {
        if (!Config::get(ApplicationConstants::YVES_SSL_ENABLED) || !Config::get(ApplicationConstants::YVES_COMPLETE_SSL_ENABLED)) {
            return;
        }

        $application->before(
            function (Request $request) {
                if (!$request->isSecure()
                    && !in_array($request->getPathInfo(), Config::get(ApplicationConstants::YVES_SSL_EXCLUDED))
                ) {
                    $fakeRequest = clone $request;
                    $fakeRequest->server->set('HTTPS', true);

                    return new RedirectResponse($fakeRequest->getUri(), 301);
                }

                return null;
            },
            255
        );
    }

}
