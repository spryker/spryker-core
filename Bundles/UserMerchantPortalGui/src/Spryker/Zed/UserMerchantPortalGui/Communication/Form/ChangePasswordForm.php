<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\UserMerchantPortalGui\Communication\UserMerchantPortalGuiCommunicationFactory getFactory()
 */
class ChangePasswordForm extends AbstractType
{
    protected const FORM_NAME = 'security-merchant-portal-gui_change-password';

    protected const FIELD_CURRENT_PASSWORD = 'current_password';
    public const FIELD_NEW_PASSWORD = 'new_password';
    protected const BUTTON_SAVE = 'save';

    protected const LABEL_CURRENT_PASSWORD = 'Current password';
    protected const LABEL_NEW_PASSWORD = 'New password';
    protected const LABEL_NEW_PASSWORD_REPEAT = 'Repeat new password';
    protected const LABEL_SAVE = 'Save';

    protected const MESSAGE_PASSWORDS_NOT_MATCHING = 'The value needs to match the New Password input.';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addCurrentPasswordField($builder)
            ->addPasswordField($builder)
            ->addSaveButton($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCurrentPasswordField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_CURRENT_PASSWORD,
            PasswordType::class,
            [
                'label' => static::LABEL_CURRENT_PASSWORD,
                'constraints' => [
                    new NotBlank(),
                    $this->getFactory()->createCurrentPasswordConstraint(),
                ],
                'attr' => [
                    'placeholder' => static::LABEL_CURRENT_PASSWORD,
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPasswordField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_NEW_PASSWORD, RepeatedType::class, [
                'invalid_message' => static::MESSAGE_PASSWORDS_NOT_MATCHING,
                'first_options' => [
                    'label' => static::LABEL_NEW_PASSWORD,
                    'attr' => ['autocomplete' => 'off'],
                ],
                'second_options' => [
                    'label' => static::LABEL_NEW_PASSWORD_REPEAT,
                    'attr' => ['autocomplete' => 'off'],
                ],
                'required' => true,
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSaveButton(FormBuilderInterface $builder)
    {
        $builder->add(static::BUTTON_SAVE, SubmitType::class, [
            'label' => static::LABEL_SAVE,
        ]);

        return $this;
    }
}
