<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Plugin\Form;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Form\FormFactoryBuilderInterface;

/**
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthFactory getFactory()
 */
class MultiFactorAuthExtensionFormPlugin extends AbstractPlugin implements FormPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds {@link \Spryker\Yves\MultiFactorAuth\Form\Type\Extension\MultiFactorAuthValidationExtension}.
     * - Adds {@link \Spryker\Yves\MultiFactorAuth\Form\Type\Extension\MultiFactorAuthHandlerExtension}.
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
        return $formFactoryBuilder->addTypeExtension(
            $this->getFactory()->createMultiFactorAuthValidationExtension(),
        )->addTypeExtension(
            $this->getFactory()->createMultiFactorAuthHandlerExtension(),
        );
    }
}
