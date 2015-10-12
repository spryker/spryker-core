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
    const REDIRECT_URL = 'redirectUrl';

    /**
     * @return self
     */
    protected function buildFormFields()
    {
        return $this
            ->addText(self::USERNAME, [
                'constraints' => [
                    new Assert\Required(),
                    new Assert\NotBlank(),
                ],
                'attr' => [
                    'placeholder' => 'Email Address',
                ],
            ])
            ->addPassword(self::PASSWORD, [
                'constraints' => [
                    new Assert\Required(),
                    new Assert\NotBlank(),
                ],
                'attr' => [
                    'placeholder' => 'Password',
                ],
            ])
        ;
    }

}
