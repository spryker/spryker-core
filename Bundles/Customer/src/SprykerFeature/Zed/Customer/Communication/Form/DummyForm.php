<?php

namespace SprykerFeature\Zed\Customer\Communication\Form;

use Generated\Shared\Transfer\CountryTransfer;
use SprykerEngine\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;

class DummyForm extends AbstractForm
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(CountryTransfer::ID_COUNTRY, 'text')
            ->add(CountryTransfer::ISO2_CODE, 'text')
            ->add(CountryTransfer::NAME, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ]
            ])
        ;
    }

    public function populateFormFields()
    {
        return $this->getDataClass();
    }

    protected function getDataClass()
    {
        return new CountryTransfer();
    }

    public function getName()
    {
        return 'dummy';
    }

}
