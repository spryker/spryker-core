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
    protected const LABEL_NEW_PASSWORD = 'New password';
    protected const LABEL_RE_ENTER_PASSWORD = 'Re-enter password';
    protected const MESSAGE_PASSWORD_FIELDS_ERROR = 'The password fields must match.';
    protected const PLACEHOLDER_NEW_PASSWORD_FIELD = 'Enter a new password';
    protected const PLACEHOLDER_RE_ENTER_PASSWORD_FIELD = 'Re-enter the new password';
    protected const BLOCK_PREFIX = 'reset_password';

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
        return static::BLOCK_PREFIX;
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
            'invalid_message' => static::MESSAGE_PASSWORD_FIELDS_ERROR,
            'first_options' => [
                'label' => static::LABEL_NEW_PASSWORD,
                'attr' => [
                    'placeholder' => static::PLACEHOLDER_NEW_PASSWORD_FIELD,
                ],
            ],
            'second_options' => [
                'label' => static::LABEL_RE_ENTER_PASSWORD,
                'attr' => [
                    'placeholder' => static::PLACEHOLDER_RE_ENTER_PASSWORD_FIELD,
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
