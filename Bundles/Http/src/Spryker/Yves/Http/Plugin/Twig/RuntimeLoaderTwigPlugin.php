<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Http\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Bridge\Twig\Extension\HttpKernelRuntime;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Twig\Environment;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

/**
 * @method \Spryker\Yves\Http\HttpFactory getFactory()
 * @method \Spryker\yves\Http\HttpConfig getConfig()
 */
class RuntimeLoaderTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    protected const SERVICE_REQUEST_STACK = 'request_stack';

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
        $twig = $this->addRuntimeLoader($twig, $container);

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    protected function addRuntimeLoader(Environment $twig, ContainerInterface $container): Environment
    {
        $runtimeComponentsCollection = $this->createRuntimeComponentsCollection($container);
        $factoryRuntimeLoader = $this->createFactoryRuntimeLoader($runtimeComponentsCollection);
        $twig->addRuntimeLoader($factoryRuntimeLoader);

        return $twig;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Fragment\FragmentHandler $fragmentHandler
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\Fragment\FragmentHandler
     */
    protected function extendFragmentHandler(FragmentHandler $fragmentHandler, ContainerInterface $container): FragmentHandler
    {
        foreach ($this->getFactory()->getFragmentHandlerPlugins() as $fragmentHandlerPlugin) {
            $fragmentHandler = $fragmentHandlerPlugin->extend($fragmentHandler, $container);
        }

        return $fragmentHandler;
    }

    /**
     * @param array $runtimeComponents
     *
     * @return \Twig\RuntimeLoader\RuntimeLoaderInterface
     */
    protected function createFactoryRuntimeLoader(array $runtimeComponents): RuntimeLoaderInterface
    {
        return new FactoryRuntimeLoader($runtimeComponents);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return array
     */
    protected function createRuntimeComponentsCollection(ContainerInterface $container): array
    {
        return [
            HttpKernelRuntime::class => function () use ($container) {
                return new HttpKernelRuntime($this->createFragmentHandler($container));
            },
        ];
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\Fragment\FragmentHandler
     */
    protected function createFragmentHandler(ContainerInterface $container): FragmentHandler
    {
        $fragmentHandler = new FragmentHandler(
            $this->getRequestStack($container)
        );

        $fragmentHandler = $this->extendFragmentHandler($fragmentHandler, $container);

        return $fragmentHandler;
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
}
