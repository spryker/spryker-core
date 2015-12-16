<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\AbstractForm;
use Spryker\Zed\User\Business\UserFacade;
use Spryker\Zed\User\Communication\Form\Constraints\CurrentPassword;

class ResetPasswordForm extends AbstractForm
{

    const CURRENT_PASSWORD = 'current_password';
    const PASSWORD = 'password';

    /**
     * @var UserFacade
     */
    protected $userFacade;

    /**
     * ResetPasswordForm constructor.
     *
     * @param UserFacade $userFacade
     */
    public function __construct(UserFacade $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * Prepares form
     *
     * @return self
     */
    protected function buildFormFields()
    {
        return $this->addPassword(
            self::CURRENT_PASSWORD,
            [
                'label' => 'Current password',
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                    new CurrentPassword([
                        'facadeUser' => $this->userFacade,
                    ]),
                ],
            ]
        )->addRepeated(
            self::PASSWORD,
            [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
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
