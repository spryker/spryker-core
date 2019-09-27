<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Validator\Plugin\Form;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormFactoryBuilderInterface;

/**
 * @method \Spryker\Yves\Validator\ValidatorFactory getFactory()
 */
class ValidatorExtensionFormPlugin extends AbstractPlugin implements FormPluginInterface
{
    protected const SERVICE_VALIDATOR = 'validator';

    /**
     * {@inheritdoc}
     * - Adds `Symfony\Component\Form\Extension\Validator\ValidatorExtension`.
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
        $formFactoryBuilder->addExtension(
            $this->createValidatorExtension($container)
        );

        return $formFactoryBuilder;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Form\Extension\Validator\ValidatorExtension
     */
    protected function createValidatorExtension(ContainerInterface $container): ValidatorExtension
    {
        return new ValidatorExtension(
            $container->get(static::SERVICE_VALIDATOR)
        );
    }
}
