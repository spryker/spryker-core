<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Gui\Plugin\FormExtension;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface;
use Spryker\Shared\Gui\Form\Type\Extension\NoValidateTypeExtension;
use Symfony\Component\Form\FormFactoryBuilderInterface;

class NoValidateTypeFormPlugin implements FormPluginInterface
{
    /**
     * {@inheritdoc}
     * - Adds `novalidate` to the form type attributes.
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
            new NoValidateTypeExtension()
        );

        return $formFactoryBuilder;
    }
}
