<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserForm extends AbstractType
{

    const OPTION_GROUP_CHOICES = 'group_choices';

    const FIELD_USERNAME = 'username';
    const FIELD_GROUP = 'group';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_PASSWORD = 'password';
    const FIELD_STATUS = 'status';

    /**
     * @return string
     */
    public function getName()
    {
        return 'user';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(self::OPTION_GROUP_CHOICES);
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
            ->addLastNameField($builder)
            ->addGroupField($builder, $options[self::OPTION_GROUP_CHOICES]);
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
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
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
                        'min' => 1,
                    ]),
                ],
                'label' => 'Assigned groups',
                'multiple' => true,
                'expanded' => true,
                'choices' => $choices,
            ]);

        return $this;
    }

}
