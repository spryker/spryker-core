<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Symfony\Bridge\Twig\Extension\SecurityExtension;
use Twig\Environment;

class SecurityTwigPlugin implements TwigPluginInterface
{
    protected const SERVICE_SECURITY_AUTHORIZATION_CHECKER = 'security.authorization_checker';

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
        if (!class_exists(SecurityExtension::class) || $container->has(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER) === false) {
            return $twig;
        }

        $twig->addExtension(new SecurityExtension($container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER)));

        return $twig;
    }
}
