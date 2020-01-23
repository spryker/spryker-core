<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\Auth\Business\AuthFacadeInterface getFacade()
 * @method \Spryker\Zed\Auth\Communication\AuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\Auth\Persistence\AuthQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Auth\AuthConfig getConfig()
 */
class LoginForm extends AbstractType
{
    public const FIELD_USERNAME = 'username';
    public const FIELD_PASSWORD = 'password';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addUserNameField($builder)
            ->addPasswordField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUserNameField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_USERNAME, EmailType::class, [
                'constraints' => [
                    new Required(),
                    new NotBlank(),
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
    protected function addPasswordField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_PASSWORD, PasswordType::class, [
                'constraints' => [
                    new Required(),
                    new NotBlank(),
                ],
                'attr' => [
                    'placeholder' => 'Password',
                    'autocomplete' => 'off',
                ],
            ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'auth';
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
