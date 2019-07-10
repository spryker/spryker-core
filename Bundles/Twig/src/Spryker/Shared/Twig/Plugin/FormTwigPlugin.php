<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Twig\Environment;

class FormTwigPlugin implements TwigPluginInterface
{
    protected const SERVICE_FORM_FACTORY = 'form.factory';

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
        if (!class_exists(FormExtension::class) || $container->has(static::SERVICE_FORM_FACTORY) === false) {
            return $twig;
        }

        $twig->addExtension(new FormExtension());

        return $twig;
    }
}
