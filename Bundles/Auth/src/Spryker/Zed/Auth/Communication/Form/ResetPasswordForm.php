<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class ResetPasswordForm extends AbstractType
{
    const FIELD_PASSWORD = 'password';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addUserNameField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUserNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_PASSWORD, 'repeated', [
            'constraints' => [
                new Required(),
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
            'type' => 'password',
            'attr' => [
                'class' => 'btn btn-default btn-block btn-outline',
            ],
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'reset_password';
    }
}
