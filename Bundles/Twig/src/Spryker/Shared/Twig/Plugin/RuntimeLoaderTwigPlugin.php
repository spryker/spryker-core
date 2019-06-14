<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Symfony\Bridge\Twig\Extension\HttpKernelRuntime;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Twig\Environment;
use Twig\RuntimeLoader\FactoryRuntimeLoader;

class RuntimeLoaderTwigPlugin implements TwigPluginInterface
{
    protected const SERVICE_REQUEST_STACK = 'request_stack';
    protected const SERVICE_FRAGMENT_RENDERERS = 'fragment.renderers';

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
        $httpKernelRuntime = function () use ($container) {
            $fragmentHandler = new FragmentHandler(
                $container->get(static::SERVICE_REQUEST_STACK),
                $container->get(static::SERVICE_FRAGMENT_RENDERERS)
            );

            return new HttpKernelRuntime($fragmentHandler);
        };

        $factoryRuntimeLoader = new FactoryRuntimeLoader([HttpKernelRuntime::class => $httpKernelRuntime]);
        $twig->addRuntimeLoader($factoryRuntimeLoader);

        return $twig;
    }
}
