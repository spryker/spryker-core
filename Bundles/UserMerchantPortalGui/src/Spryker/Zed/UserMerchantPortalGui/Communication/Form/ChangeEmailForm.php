<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\UserMerchantPortalGui\Communication\UserMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\UserMerchantPortalGui\UserMerchantPortalGuiConfig getConfig()
 */
class ChangeEmailForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_EMAIL = 'email';

    /**
     * @var string
     */
    public const KEY_ID_USER = 'id_user';

    /**
     * @var string
     */
    public const OPTION_IS_EMAIL_UNIQUENESS_VALIDATION_ENABLED = 'is_email_uniqueness_validation_enabled';

    /**
     * @var string
     */
    protected const FORM_NAME = 'security-merchant-portal-gui_change-email';

    /**
     * @var string
     */
    protected const BUTTON_SAVE = 'save';

    /**
     * @var string
     */
    protected const FIELD_CURRENT_PASSWORD = 'current_password';

    /**
     * @var string
     */
    protected const LABEL_CURRENT_PASSWORD = 'Current password';

    /**
     * @var string
     */
    protected const LABEL_NEW_EMAIL = 'New email';

    /**
     * @var string
     */
    protected const LABEL_SAVE = 'Save';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addCurrentPasswordField($builder)
            ->addEmailField($builder, $options)
            ->addSaveButton($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            static::OPTION_IS_EMAIL_UNIQUENESS_VALIDATION_ENABLED => true,
        ]);
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
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder, array $options)
    {
        $formData = $builder->getData();

        $constraints = [
            new NotBlank(),
            new Email(),
        ];

        if ($options[static::OPTION_IS_EMAIL_UNIQUENESS_VALIDATION_ENABLED]) {
            $constraints[] = $this->getFactory()->createUniqueUserEmailConstraint($formData[static::KEY_ID_USER]);
        }

        $builder
            ->add(static::FIELD_EMAIL, EmailType::class, [
                'required' => true,
                'label' => static::LABEL_NEW_EMAIL,
                'sanitize_xss' => true,
                'constraints' => $constraints,
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
