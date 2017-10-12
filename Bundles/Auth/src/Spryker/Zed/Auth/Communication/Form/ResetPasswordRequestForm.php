<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class ResetPasswordRequestForm extends AbstractType
{
    const FIELD_EMAIL = 'email';
    const FIELD_SUBMIT = 'submit';
    const FIELD_LOGIN = 'login';

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
            ->addSubmitField($builder)
            ->addLoginField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_EMAIL, 'text', [
                'constraints' => [
                    new Required(),
                    new NotBlank(),
                    new Email(),
                ],
                'attr' => [
                    'placeholder' => 'Email Address',
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
            ->add(self::FIELD_SUBMIT, 'submit', [
                'label' => 'Recover password',
                'attr' => [
                    'class' => 'btn btn-success btn-block btn-outline',
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLoginField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_LOGIN, 'url', [
                'attr' => [
                    'href' => '/auth/login',
                    'class' => 'btn btn-primary btn-block btn-outline',
                    'title' => 'Login',
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
