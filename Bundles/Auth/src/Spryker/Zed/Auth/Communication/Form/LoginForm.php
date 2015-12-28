<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Communication\Form;

use Spryker\Shared\Gui\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;

class LoginForm extends AbstractForm
{

    const USERNAME = 'username';
    const PASSWORD = 'password';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::USERNAME, 'text', [
            'constraints' => [
                $this->getConstraints()->createConstraintRequired(),
                $this->getConstraints()->createConstraintNotBlank(),
            ],
            'attr' => [
                'placeholder' => 'Email Address',
            ],
        ])
        ->add(self::PASSWORD, 'password', [
            'constraints' => [
                $this->getConstraints()->createConstraintRequired(),
                $this->getConstraints()->createConstraintNotBlank(),
            ],
            'attr' => [
                'placeholder' => 'Password',
            ],
        ]);
    }

    public function populateFormFields()
    {
        return [];
    }

    protected function getDataClass()
    {
        return null;
    }

    public function getName()
    {
        return 'auth';
    }
}
