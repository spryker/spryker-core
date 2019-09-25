<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Symfony\Bridge\Twig\Extension\HttpKernelExtension;
use Symfony\Component\HttpKernel\Fragment\HIncludeFragmentRenderer;
use Twig\Environment;

class HttpKernelTwigPlugin implements TwigPluginInterface
{
    protected const SERVICE_FRAGMENT_HANDLER = 'fragment.handler';
    protected const SERVICE_FRAGMENT_RENDERER_HINCLUDE = 'fragment.renderer.hinclude';

    /**
     * {@inheritDoc}
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
        if (!class_exists(HttpKernelExtension::class) || $container->has(static::SERVICE_FRAGMENT_HANDLER) === false) {
            return $twig;
        }

        $fragmentHandlerHinclude = $this->getFragmentRendererHinclude($container);
        $fragmentHandlerHinclude->setTemplating($twig);

        $twig->addExtension(new HttpKernelExtension());

        return $twig;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\Fragment\HIncludeFragmentRenderer
     */
    protected function getFragmentRendererHinclude(ContainerInterface $container): HIncludeFragmentRenderer
    {
        return $container->get(static::SERVICE_FRAGMENT_RENDERER_HINCLUDE);
    }
}
