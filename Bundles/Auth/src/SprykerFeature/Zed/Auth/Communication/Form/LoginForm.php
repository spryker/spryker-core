<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints as Assert;

class LoginForm extends AbstractForm
{
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const SUBMIT = 'submit';

    /**
     * @return $this
     */
    protected function buildFormFields()
    {
        return $this
            ->addText(self::USERNAME, [
                'constraints' => [
                    new Assert\Required(),
                    new Assert\NotBlank(),
                ]
            ])
            ->addPassword(self::PASSWORD, [
                'constraints' => [
                    new Assert\Required(),
                    new Assert\NotBlank(),
                ]
            ])

            ->addSubmit(self::SUBMIT, [
                'label' => 'Login',
                'attr' => [
                    'class' => 'btn btn-success btn-block',
                ]
            ])
        ;
    }

    /**
     * @return $this
     */
    protected function populateFormFields()
    {
        return [];
    }

}
