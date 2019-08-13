<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;

class DebugTwigPlugin implements TwigPluginInterface
{
    protected const SERVICE_DEBUG = 'debug';

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
        if ($container->has(static::SERVICE_DEBUG) === false || $container->get(static::SERVICE_DEBUG) === false) {
            return $twig;
        }

        $twig->addExtension(new DebugExtension());

        return $twig;
    }
}
