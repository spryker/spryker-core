<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;

class ResetPasswordForm extends AbstractForm
{

    const FIELD_PASSWORD = 'password';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_PASSWORD, 'repeated', [
            'constraints' => [
                $this->getConstraints()->createConstraintNotBlank(),
            ],
            'invalid_message' => 'The password fields must match.',
            'first_options' => [
                'label' => 'Password',
                'attr' => [
                    'placeholder' => 'Password',
                ],
            ],
            'second_options' => [
                'label' => 'Repeat Password',
                'attr' => [
                    'placeholder' => 'Repeat Password',
                ],
            ],
            'required' => true,
            'type' => 'password',
            'attr' => [
                'class' => 'btn btn-default btn-block btn-outline',
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
