<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints;
use SprykerFeature\Zed\User\Communication\Form\Constraints\CurrentPassword;

class ResetPasswordForm extends AbstractForm
{
    const CURRENT_PASSWORD = 'current_password';
    const PASSWORD = 'password';

    /**
     * Prepares form
     *
     * @return $this
     */
    protected function buildFormFields()
    {
        return $this->addPassword(
            self::CURRENT_PASSWORD,
            [
                'label'       => 'Current password',
                'constraints' => [
                    new Constraints\NotBlank(),
                    new CurrentPassword([
                        'facadeUser' => $this->getLocator()->user()->facade(),
                    ]),
                ],
            ]
        )->addRepeated(
            self::PASSWORD,
            [
                'constraints' => [
                    new Constraints\NotBlank(),
                ],
                'invalid_message' => 'The password fields must match.',
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'required' => true,
                'type' => 'password',
            ]
        );
    }

    /**
     * Set the values for fields
     *
     * @return array
     */
    protected function populateFormFields()
    {
        return [];
    }
}
