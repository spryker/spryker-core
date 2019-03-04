<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Form\Plugin\FormExtension;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface;
use Symfony\Component\Form\Extension\HttpFoundation\Type\FormTypeHttpFoundationExtension;
use Symfony\Component\Form\FormFactoryBuilderInterface;

class HttpFoundationFormTypeExtensionPlugin implements FormPluginInterface
{
    /**
     * {@inheritdoc}
     * - Adds the basic Symfony HttpFoundation extension.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormFactoryBuilderInterface $formFactoryBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Form\FormFactoryBuilderInterface
     */
    public function extend(FormFactoryBuilderInterface $formFactoryBuilder, ContainerInterface $container): FormFactoryBuilderInterface
    {
        $formFactoryBuilder->addTypeExtension(
            new FormTypeHttpFoundationExtension()
        );

        return $formFactoryBuilder;
    }
}
