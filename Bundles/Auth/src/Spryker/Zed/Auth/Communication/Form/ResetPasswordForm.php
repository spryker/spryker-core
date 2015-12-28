<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Communication\Form;

use Spryker\Shared\Gui\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;

class ResetPasswordForm extends AbstractForm
{

    const PASSWORD = 'password';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder->add(self::PASSWORD, 'repeated', [
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
