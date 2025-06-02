<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface getRepository()
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 */
class BaseMultiFactorAuthForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_USERNAME = 'username';

    /**
     * @var string
     */
    protected const FIELD_IS_ACTIVATION = 'is_activation';

    /**
     * @var string
     */
    protected const FIELD_IS_DEACTIVATION = 'is_deactivation';

    /**
     * @var string
     */
    protected const FIELD_TYPE_TO_SET_UP = 'type_to_set_up';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            static::FIELD_USERNAME => null,
            static::FIELD_IS_ACTIVATION => null,
            static::FIELD_IS_DEACTIVATION => null,
            static::FIELD_TYPE_TO_SET_UP => null,
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
        $this->addUsernameHiddenField($builder, $options);
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
    protected function addUsernameHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_USERNAME, HiddenType::class, [
            'mapped' => false,
            'data' => $options[static::FIELD_USERNAME],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addIsActivationHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_IS_ACTIVATION, HiddenType::class, [
            'data' => $options[static::FIELD_IS_ACTIVATION],
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
        $builder->add(static::FIELD_IS_DEACTIVATION, HiddenType::class, [
            'data' => $options[static::FIELD_IS_DEACTIVATION],
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
        $builder->add(static::FIELD_TYPE_TO_SET_UP, HiddenType::class, [
            'data' => $options[static::FIELD_TYPE_TO_SET_UP],
        ]);

        return $this;
    }
}
