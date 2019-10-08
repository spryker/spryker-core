<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Http\Plugin\Http;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\HttpExtension\Dependency\Plugin\FragmentHandlerPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Symfony\Component\HttpKernel\Fragment\FragmentRendererInterface;
use Symfony\Component\HttpKernel\Fragment\HIncludeFragmentRenderer;

/**
 * @method \Spryker\Yves\Http\HttpFactory getFactory()
 * @method \Spryker\Yves\Http\HttpConfig getConfig()
 */
class HIncludeRendererFragmentHandlerPlugin extends AbstractPlugin implements FragmentHandlerPluginInterface
{
    protected const SERVICE_CHARSET = 'charset';

    /**
     * @uses \Spryker\Yves\Twig\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     */
    protected const SERVICE_TWIG = 'twig';

    /**
     * {@inheritDoc}
     * - Adds `HIncludeFragmentRenderer`.
     *
     * @api
     *
     * @param \Symfony\Component\HttpKernel\Fragment\FragmentHandler $fragmentHandler
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\Fragment\FragmentHandler
     */
    public function extend(FragmentHandler $fragmentHandler, ContainerInterface $container): FragmentHandler
    {
        $fragmentHandler->addRenderer($this->createHIncludeRenderer($container));

        return $fragmentHandler;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\Fragment\FragmentRendererInterface
     */
    protected function createHIncludeRenderer(ContainerInterface $container): FragmentRendererInterface
    {
        $renderer = new HIncludeFragmentRenderer(
            $container->get(static::SERVICE_TWIG),
            $this->getFactory()->createUriSigner(),
            $this->getConfig()->getHIncludeRendererGlobalTemplate(),
            $container->get(static::SERVICE_CHARSET)
        );
        $renderer->setFragmentPath($this->getConfig()->getHttpFragmentPath());

        return $renderer;
    }
}
