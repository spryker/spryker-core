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
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\UserMerchantPortalGui\Communication\UserMerchantPortalGuiCommunicationFactory getFactory()
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
            ->addEmailField($builder)
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
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $formData = $builder->getData();

        $builder
            ->add(static::FIELD_EMAIL, EmailType::class, [
                'required' => true,
                'label' => static::LABEL_NEW_EMAIL,
                'sanitize_xss' => true,
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    $this->getFactory()->createUniqueUserEmailConstraint($formData[static::KEY_ID_USER]),
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
