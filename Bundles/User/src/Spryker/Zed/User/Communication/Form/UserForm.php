<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Form;

use Spryker\Zed\User\Business\UserFacadeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserForm extends AbstractType
{
    const OPTION_GROUP_CHOICES = 'group_choices';
    const GROUP_UNIQUE_USERNAME_CHECK = 'unique_email_check';

    const FIELD_USERNAME = 'username';
    const FIELD_GROUP = 'group';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_PASSWORD = 'password';
    const FIELD_STATUS = 'status';

    /**
     * @var \Spryker\Zed\User\Business\UserFacadeInterface
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\User\Business\UserFacadeInterface $userFacade
     */
    public function __construct(UserFacadeInterface $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(self::OPTION_GROUP_CHOICES);

        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $defaultData = $form->getConfig()->getData();
                $submittedData = $form->getData();

                if (array_key_exists(self::FIELD_USERNAME, $defaultData) === false ||
                    $defaultData[self::FIELD_USERNAME] !== $submittedData[self::FIELD_USERNAME]
                ) {
                    return [Constraint::DEFAULT_GROUP, self::GROUP_UNIQUE_USERNAME_CHECK];
                }

                return [Constraint::DEFAULT_GROUP];
            },
        ]);
    }

    /**
     * @deprecated Use `configureOptions()` instead.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
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
            ->addPasswordField($builder)
            ->addFirstNameField($builder)
            ->addLastNameField($builder);

        $groupChoices = $options[self::OPTION_GROUP_CHOICES];
        if ($groupChoices) {
            $this->addGroupField($builder, $options[self::OPTION_GROUP_CHOICES]);
        }
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_USERNAME, 'text', [
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
    protected function addPasswordField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_PASSWORD, 'repeated', [
                'constraints' => [
                    new NotBlank(),
                ],
                'invalid_message' => 'The password fields must match.',
                'first_options' => ['label' => 'Password', 'attr' => ['autocomplete' => 'off']],
                'second_options' => ['label' => 'Repeat Password', 'attr' => ['autocomplete' => 'off']],
                'required' => true,
                'type' => 'password',
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
            ->add(self::FIELD_FIRST_NAME, 'text', [
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
            ->add(self::FIELD_LAST_NAME, 'text', [
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addGroupField(FormBuilderInterface $builder, array $choices)
    {
        $builder
            ->add(self::FIELD_GROUP, 'choice', [
                'constraints' => [
                    new Choice([
                        'choices' => array_keys($choices),
                        'multiple' => true,
                    ]),
                ],
                'label' => 'Assigned groups',
                'multiple' => true,
                'expanded' => true,
                'choices' => $choices,
            ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createUniqueEmailConstraint()
    {
        return new Callback([
            'methods' => [
                function ($email, ExecutionContextInterface $contextInterface) {
                    if ($this->userFacade->hasUserByUsername($email)) {
                        $contextInterface->addViolation('User with email "{{ username }}" already exists.', [
                            '{{ username }}' => $email,
                        ]);
                    }
                },
            ],
            'groups' => [self::GROUP_UNIQUE_USERNAME_CHECK],
        ]);
    }
}
