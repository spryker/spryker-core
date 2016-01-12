<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\AbstractForm;
use Spryker\Zed\User\Business\UserFacade;
use Spryker\Zed\User\Communication\Form\Constraints\CurrentPassword;
use Symfony\Component\Form\FormBuilderInterface;

class ResetPasswordForm extends AbstractForm
{

    const FIELD_CURRENT_PASSWORD = 'current_password';
    const FIELD_PASSWORD = 'password';

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
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_CURRENT_PASSWORD, 'password', [
            'label' => 'Current password',
            'constraints' => [
                $this->getConstraints()->createConstraintNotBlank(),
                new CurrentPassword([
                    'facadeUser' => $this->userFacade,
                ]),
            ],
        ])
        ->add(self::FIELD_PASSWORD, 'repeated', [
            'constraints' => [
                $this->getConstraints()->createConstraintNotBlank(),
            ],
            'invalid_message' => 'The password fields must match.',
            'first_options' => ['label' => 'Password'],
            'second_options' => ['label' => 'Repeat Password'],
            'required' => true,
            'type' => 'password',
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
     * Set the values for fields
     *
     * @return array
     */
    public function populateFormFields()
    {
        return [];
    }

}
