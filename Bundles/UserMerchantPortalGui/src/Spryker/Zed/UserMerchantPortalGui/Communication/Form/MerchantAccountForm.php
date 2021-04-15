<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\SelectType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\UserMerchantPortalGui\Communication\Form\Constraint\UniqueUserEmailConstraint;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\UserMerchantPortalGui\Communication\UserMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\UserMerchantPortalGui\Business\UserMerchantPortalGuiFacadeInterface getFacade()
 */
class MerchantAccountForm extends AbstractType
{
    protected const FORM_NAME = 'user-merchant-portal-gui_merchant-account';

    protected const FIELD_FIRST_NAME = 'first_name';
    protected const FIELD_LAST_NAME = 'last_name';
    protected const FIELD_USERNAME = 'username';
    public const FIELD_FK_LOCALE = 'fk_locale';
    protected const BUTTON_SAVE = 'save';

    public const OPTIONS_LOCALE = 'options_locale';

    protected const LABEL_FIRST_NAME = 'First name';
    protected const LABEL_LAST_NAME = 'Last name';
    protected const LABEL_USERNAME = 'E-mail';
    protected const LABEL_PASSWORD = 'Password';
    protected const LABEL_FK_LOCALE = 'Language';
    protected const LABEL_SAVE = 'Save';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setRequired(static::OPTIONS_LOCALE);

        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $defaultData = $form->getConfig()->getData();
                $submittedData = $form->getData();

                if (
                    array_key_exists(static::FIELD_USERNAME, $defaultData) === false
                    || $defaultData[static::FIELD_USERNAME] !== $submittedData[static::FIELD_USERNAME]
                ) {
                    return [
                        Constraint::DEFAULT_GROUP,
                        UniqueUserEmailConstraint::GROUP_UNIQUE_USERNAME_CHECK,
                    ];
                }

                return [Constraint::DEFAULT_GROUP];
            },
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addFirstNameField($builder)
            ->addLastNameField($builder)
            ->addEmailField($builder)
            ->addFkLocaleField($builder, $options)
            ->addSaveButton($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFirstNameField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_FIRST_NAME, TextType::class, [
                'label' => static::LABEL_FIRST_NAME,
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
    protected function addLastNameField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_LAST_NAME, TextType::class, [
                'label' => static::LABEL_LAST_NAME,
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
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_USERNAME, TextType::class, [
                'label' => static::LABEL_USERNAME,
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    $this->getFactory()->createUniqueUserEmailConstraint(),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addFkLocaleField(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(static::FIELD_FK_LOCALE, SelectType::class, [
                'label' => static::LABEL_FK_LOCALE,
                'choices' => $options[static::OPTIONS_LOCALE],
                'required' => true,
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
