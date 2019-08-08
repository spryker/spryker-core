<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Communication\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \Spryker\Zed\ZedNavigation\Communication\ZedNavigationCommunicationFactory getFactory()
 * @method \Spryker\Zed\ZedNavigation\ZedNavigationConfig getConfig()
 * @method \Spryker\Zed\ZedNavigation\Business\ZedNavigationFacadeInterface getFacade()
 */
class ZedNavigationTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    protected const URI_SUFFIX_INDEX = '\/index$';
    protected const URI_SUFFIX_SLASH = '\/$';

    protected const TWIG_FUNCTION_NAME_NAVIGATION = 'navigation';
    protected const TWIG_FUNCTION_NAME_BREADCRUMBS = 'breadcrumb';

    protected const TWIG_GLOBAL_VARIABLE_NAME_NAVIGATION = 'navigation';
    protected const TWIG_GLOBAL_VARIABLE_NAME_BREADCRUMBS = 'breadcrumb';

    protected const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @var array|null
     */
    protected $navigation;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig = $this->addTwigFunctions($twig, $container);
        $twig = $this->addTwigGlobalVariables($twig);

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    protected function addTwigFunctions(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addFunction($this->getNavigationFunction($container));
        $twig->addFunction($this->getBreadcrumbFunction($container));

        return $twig;
    }

    /**
     * @deprecated This is added only for BC. Use `ZedNavigationTwigPlugin::addTwigFunctions()` instead. Also use `navigation()` and `breadcrumb()` functions in twig instead of corespondent twig global variables.
     *
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function addTwigGlobalVariables(Environment $twig): Environment
    {
        $navigation = $this->buildNavigation(Request::createFromGlobals());
        $breadcrumbs = $navigation['path'];

        $twig->addGlobal(static::TWIG_GLOBAL_VARIABLE_NAME_NAVIGATION, $navigation);
        $twig->addGlobal(static::TWIG_GLOBAL_VARIABLE_NAME_BREADCRUMBS, $breadcrumbs);

        return $twig;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\TwigFunction
     */
    protected function getNavigationFunction(ContainerInterface $container): TwigFunction
    {
        $navigation = new TwigFunction(static::TWIG_FUNCTION_NAME_NAVIGATION, function () use ($container) {
            $request = $this->getRequest($container);
            $navigation = $this->buildNavigation($request);

            return $navigation;
        });

        return $navigation;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\TwigFunction
     */
    protected function getBreadcrumbFunction(ContainerInterface $container): TwigFunction
    {
        $navigation = new TwigFunction(static::TWIG_FUNCTION_NAME_BREADCRUMBS, function () use ($container) {
            $request = $this->getRequest($container);
            $navigation = $this->buildNavigation($request);

            return $navigation['path'];
        });

        return $navigation;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest(ContainerInterface $container): Request
    {
        return $this->getRequestStack($container)->getCurrentRequest();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function buildNavigation(Request $request): array
    {
        if ($this->navigation === null) {
            $uri = $this->removeUriSuffix($request->getPathInfo());
            $this->navigation = [];
            if ($this->getConfig()->isNavigationEnabled()) {
                $this->navigation = $this->getFacade()
                    ->buildNavigation($uri);
            }
        }

        return $this->navigation;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    protected function getRequestStack(ContainerInterface $container): RequestStack
    {
        return $container->get(static::SERVICE_REQUEST_STACK);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function removeUriSuffix(string $path): string
    {
        $pattern = sprintf('/%s|%s/m', static::URI_SUFFIX_INDEX, static::URI_SUFFIX_SLASH);

        return preg_replace($pattern, '', $path);
    }
}
