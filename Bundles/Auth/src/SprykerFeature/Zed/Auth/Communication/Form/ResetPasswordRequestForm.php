<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;

class ResetPasswordRequestForm extends AbstractForm
{

    const EMAIL = 'email';
    const SUBMIT = 'submit';
    const LOGIN = 'login';

    /**
     * @return self;
     */
    protected function buildFormFields()
    {
        return $this
            ->addText(self::EMAIL, [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                    $this->getConstraints()->createConstraintRequired(),
                    $this->getConstraints()->createConstraintEmail(),
                ],
                'attr' => [
                    'placeholder' => 'Email Address',
                ],
            ])
            ->addSubmit(self::SUBMIT, [
                'label' => 'Recover password',
                'attr' => [
                    'class' => 'btn btn-primary btn-block btn-outline',
                ],
            ])
            ->addUrl(self::LOGIN, [
                'attr' => [
                    'href' => '/auth/login',
                    'class' => 'btn btn-success btn-block btn-outline',
                    'title' => 'Login',
                ],
            ])
        ;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        return [];
    }

}
