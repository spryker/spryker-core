<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class LoginForm extends AbstractType
{

    const FIELD_USERNAME = 'username';
    const FIELD_PASSWORD = 'password';

    /**
     * @param FormBuilderInterface $builder
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
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addUserNameField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_USERNAME, 'text', [
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
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addPasswordField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_PASSWORD, 'password', [
                'constraints' => [
                    new Required(),
                    new NotBlank(),
                ],
                'attr' => [
                    'placeholder' => 'Password',
                ],
            ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'auth';
    }

}
