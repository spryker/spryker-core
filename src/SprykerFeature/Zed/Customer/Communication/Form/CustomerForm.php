<?php

namespace SprykerFeature\Zed\Customer\Communication\Form;

use Symfony\Component\Validator\Constraints as Assert;
use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;

class CustomerForm extends AbstractForm
{
    /**
     * @return void
     */
    public function addFormFields()
    {
        $this->addField('id_customer')
            ->setConstraints([
                new Assert\Type([
                    'type' => 'integer'
                ])
            ])
        ;

        $this->addField('email')
            ->setConstraints([
                new Assert\Required([
                    new Assert\Type([
                        'type' => 'string'
                    ]),
                    new Assert\NotBlank()
                ])
            ])
        ;

        $this->addField('salutation')
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string'
                ])
            ])
        ;

        $this->addField('first_name')
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string'
                ]),
            ])
        ;

        $this->addField('last_name')
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string'
                ]),
            ])
        ;

        $this->addField('gender')
            ->setAccepts($this->getGenderOptions())
            ->setConstraints([
                new Assert\Type([
                    'type' => 'integer'
                ]),
                new Assert\Choice([
                    'choices' => array_column($this->getGenderOptions(), 'value'),
                ])
            ])
            ->setValueHook(function ($value) {
                return $value ? (int)$value : null;
            })
        ;
    }

    /**
     * @return array
     */
    public function getDefaultData()
    {
        $customerTransfer = $this->locator->customer()->transferCustomer();
        $customerTransfer->setIdCustomer($this->stateContainer->getRequestValue('id'));
        $customerTransfer = $this->locator->customer()->facade()->getCustomer($customerTransfer);

        return $customerTransfer->toArray();
    }

    /**
     * @return array
     */
    protected function getGenderOptions()
    {
        return [
            ["value" => 0, "label" => "customer.profile.gender.unknown"],
            ["value" => 1, "label" => "customer.profile.gender.male"],
            ["value" => 2, "label" => "customer.profile.gender.female"],
        ];
    }
}
