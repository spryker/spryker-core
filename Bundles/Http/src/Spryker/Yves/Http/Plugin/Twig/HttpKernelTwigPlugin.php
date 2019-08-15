<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Http\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;

/**
 * @method \Spryker\Yves\Http\HttpFactory getFactory()
 * @method \Spryker\Yves\Http\HttpConfig getConfig()
 */
class HttpKernelTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * {@inheritdoc}
     * - Adds `HttpKernelExtension`.
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
        $twig->addExtension($this->getFactory()->createHttpKernelException());

        return $twig;
    }
}
