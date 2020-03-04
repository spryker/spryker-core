<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\MerchantUserGui\Communication\MerchantUserGuiCommunicationFactory getFactory()
 */
class MerchantUserCreateForm extends AbstractType
{
    public const FIELD_USERNAME = 'username';
    public const FIELD_FIRST_NAME = 'first_name';
    public const FIELD_LAST_NAME = 'last_name';
    public const FIELD_MERCHANT_ID = 'id_merchant';
    public const FIELD_MERCHANT_USER_ID = 'id_merchant_user';
    public const FIELD_PASSWORD = 'password';
    public const FIELD_STATUS = 'status';

    protected const GROUP_UNIQUE_USERNAME_CHECK = 'unique_email_check';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $defaultData = $form->getConfig()->getData();
                $submittedData = $form->getData();

                if (
                    array_key_exists(static::FIELD_USERNAME, $defaultData) === false ||
                    $defaultData[static::FIELD_USERNAME] !== $submittedData[static::FIELD_USERNAME]
                ) {
                    return [Constraint::DEFAULT_GROUP, static::GROUP_UNIQUE_USERNAME_CHECK];
                }

                return [Constraint::DEFAULT_GROUP];
            },
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'merchant-user';
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
            ->addEmailField($builder)
            ->addFirstNameField($builder)
            ->addLastNameField($builder);
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
                'label' => 'E-mail',
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    $this->createUniqueEmailConstraint(),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMerchantId(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_MERCHANT_ID, HiddenType::class, [
                'label' => 'id_merchant',
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
    protected function addMerchantUserId(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_MERCHANT_USER_ID, HiddenType::class, [
                'label' => 'id_merchant_user',
            ]);

        return $this;
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
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createUniqueEmailConstraint()
    {
        return new Callback([
            'callback' => function ($email, ExecutionContextInterface $contextInterface) {
                if ($this->getFactory()->getUserFacade()->hasUserByUsername($email)) {
                    $contextInterface->addViolation('User with email "{{ username }}" already exists.', [
                        '{{ username }}' => $email,
                    ]);
                }
            },
            'groups' => [self::GROUP_UNIQUE_USERNAME_CHECK],
        ]);
    }
}
