<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\SecurityMerchantPortalGui\Communication\SecurityMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig getConfig()
 */
class MerchantResetPasswordForm extends AbstractType
{
    public const FIELD_PASSWORD = 'password';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addPasswordField($builder);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'reset_password';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPasswordField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_PASSWORD, RepeatedType::class, [
            'constraints' => [
                new NotBlank(),
            ],
            'invalid_message' => 'The password fields must match.',
            'first_options' => [
                'label' => 'Password',
                'attr' => [
                    'placeholder' => 'Password',
                ],
            ],
            'second_options' => [
                'label' => 'Repeat Password',
                'attr' => [
                    'placeholder' => 'Repeat Password',
                ],
            ],
            'required' => true,
            'type' => PasswordType::class,
            'attr' => [
                'class' => 'btn btn-default btn-block btn-outline',
            ],
        ]);

        return $this;
    }
}
