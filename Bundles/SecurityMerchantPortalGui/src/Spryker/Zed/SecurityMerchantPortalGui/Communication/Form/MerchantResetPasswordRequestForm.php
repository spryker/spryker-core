<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\SecurityMerchantPortalGui\Communication\SecurityMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig getConfig()
 */
class MerchantResetPasswordRequestForm extends AbstractType
{
    public const FIELD_EMAIL = 'email';
    public const FIELD_SUBMIT = 'submit';
    protected const LABEL_EMAIL = 'email';
    protected const LABEL_SEND_EMAIL = 'Send email';
    protected const MESSAGE_VALIDATION_NOT_BLANK_ERROR = 'The value cannot be blank. Please fill in this input';
    protected const MESSAGE_VALIDATION_EMAIL_FORMAT_ERROR = 'Please fill in a valid email address for this input';
    protected const PLACEHOLDER_EMAIL_FIELD = 'example@spryker.com';
    protected const BLOCK_PREFIX = 'reset_password';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addEmailField($builder)
            ->addSubmitField($builder);
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
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_EMAIL, TextType::class, [
                'label' => static::LABEL_EMAIL,
                'constraints' => [
                    new NotBlank(['message' => static::MESSAGE_VALIDATION_NOT_BLANK_ERROR]),
                    new Email(['message' => static::MESSAGE_VALIDATION_EMAIL_FORMAT_ERROR]),
                ],
                'attr' => [
                    'placeholder' => static::PLACEHOLDER_EMAIL_FIELD,
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSubmitField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_SUBMIT, SubmitType::class, [
                'label' => static::LABEL_SEND_EMAIL,
            ]);

        return $this;
    }
}
