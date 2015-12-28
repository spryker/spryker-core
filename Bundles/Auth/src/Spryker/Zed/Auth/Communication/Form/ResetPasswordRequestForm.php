<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Communication\Form;

use Spryker\Shared\Gui\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;

class ResetPasswordRequestForm extends AbstractForm
{

    const EMAIL = 'email';
    const SUBMIT = 'submit';
    const LOGIN = 'login';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::EMAIL, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                    $this->getConstraints()->createConstraintRequired(),
                    $this->getConstraints()->createConstraintEmail(),
                ],
                'attr' => [
                    'placeholder' => 'Email Address',
                ],
            ])
            ->add(self::SUBMIT, 'submit', [
                'label' => 'Recover password',
                'attr' => [
                    'class' => 'btn btn-primary btn-block btn-outline',
                ],
            ])
            ->add(self::LOGIN, 'url', [
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
