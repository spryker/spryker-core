<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Spryker\Yves\MultiFactorAuth\Controller\CustomerMultiFactorAuthFlowController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class BaseMultiFactorAuthForm extends AbstractType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            CustomerMultiFactorAuthFlowController::IS_ACTIVATION => null,
            CustomerMultiFactorAuthFlowController::IS_DEACTIVATION => null,
            CustomerMultiFactorAuthFlowController::TYPE_TO_SET_UP => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIsActivationHiddenField($builder, $options);
        $this->addIsDeactivationHiddenField($builder, $options);
        $this->addTypeToSetUpHiddenField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addIsActivationHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(CustomerMultiFactorAuthFlowController::IS_ACTIVATION, HiddenType::class, [
            'data' => $options[CustomerMultiFactorAuthFlowController::IS_ACTIVATION],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addIsDeactivationHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(CustomerMultiFactorAuthFlowController::IS_DEACTIVATION, HiddenType::class, [
            'data' => $options[CustomerMultiFactorAuthFlowController::IS_DEACTIVATION],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addTypeToSetUpHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(CustomerMultiFactorAuthFlowController::TYPE_TO_SET_UP, HiddenType::class, [
            'data' => $options[CustomerMultiFactorAuthFlowController::TYPE_TO_SET_UP],
        ]);

        return $this;
    }
}
