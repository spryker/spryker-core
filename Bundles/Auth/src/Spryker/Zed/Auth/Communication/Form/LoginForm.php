<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Communication\Form;

use Spryker\Shared\Gui\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;

class LoginForm extends AbstractForm
{

    const FIELD_USERNAME = 'username';
    const FIELD_PASSWORD = 'password';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_USERNAME, 'text', [
            'constraints' => [
                $this->getConstraints()->createConstraintRequired(),
                $this->getConstraints()->createConstraintNotBlank(),
            ],
            'attr' => [
                'placeholder' => 'Email Address',
            ],
        ])
        ->add(self::FIELD_PASSWORD, 'password', [
            'constraints' => [
                $this->getConstraints()->createConstraintRequired(),
                $this->getConstraints()->createConstraintNotBlank(),
            ],
            'attr' => [
                'placeholder' => 'Password',
            ],
        ]);
    }

    /**
     * @return array
     */
    public function populateFormFields()
    {
        return [];
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
        return 'auth';
    }

}
