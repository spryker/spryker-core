<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Communication\Form;

use Spryker\Shared\Gui\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;

class ResetPasswordRequestForm extends AbstractForm
{

    const FIELD_EMAIL = 'email';
    const FIELD_SUBMIT = 'submit';
    const FIELD_LOGIN = 'login';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_EMAIL, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                    $this->getConstraints()->createConstraintRequired(),
                    $this->getConstraints()->createConstraintEmail(),
                ],
                'attr' => [
                    'placeholder' => 'Email Address',
                ],
            ])
            ->add(self::FIELD_SUBMIT, 'submit', [
                'label' => 'Recover password',
                'attr' => [
                    'class' => 'btn btn-primary btn-block btn-outline',
                ],
            ])
            ->add(self::FIELD_LOGIN, 'url', [
                'attr' => [
                    'href' => '/auth/login',
                    'class' => 'btn btn-success btn-block btn-outline',
                    'title' => 'Login',
                ],
            ]);
    }

    /**
     * @return null
     */
    protected function getDataClass()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'reset_password';
    }

    /**
     * @return array
     */
    public function populateFormFields()
    {
        return [];
    }
}
