<?php

namespace SprykerFeature\Zed\Customer\Communication\Form;

use Symfony\Component\Validator\Constraints as Assert;
use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;

class AddressForm extends AbstractForm
{
    public function addFormFields()
    {
        $this->addField('id_customer_address')
            ->setConstraints([
                new Assert\Required([
                    new Assert\Type([
                        'type' => 'int'
                    ]),
                ])
            ])
        ;

        $this->addField('fk_customer')
            ->setConstraints([
                new Assert\Required([
                    new Assert\Type([
                        'type' => 'int'
                    ]),
                ])
            ])
        ;

        $this->addField('name')
            ->setConstraints([
                new Assert\Required([
                    new Assert\Type([
                        'type' => 'string'
                    ]),
                    new Assert\NotBlank()
                ])
            ])
        ;

        $this->addField('company')
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string'
                ])
            ])
        ;

        $this->addField('address1')
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string'
                ]),
            ])
        ;

        $this->addField('address2')
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string'
                ]),
            ])
        ;

        $this->addField('zip_code')
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string'
                ]),
            ])
        ;

        $this->addField('city')
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string'
                ]),
                new Assert\NotBlank()
            ])
        ;

        $this->addField('fk_misc_country')
            ->setAccepts($this->getCountryOptions())
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string'
                ]),
                new Assert\Choice([
                    'choices' => array_column($this->getCountryOptions(), 'value'),
                ]),
            ])
            ->setValueHook(function ($value) {
                return $value ? (int)$value : null;
            })
        ;

        $this->addField('state')
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string'
                ])
            ])
        ;
    }

    /**
     * @return array
     */
    public function getDefaultData()
    {
        $addressId = $this->stateContainer->getRequestValue('id_customer_address');
        if (!$addressId) {
            return [];
        }

        $addressTransfer = $this->locator->customer()->transferAddress();
        $addressTransfer->setIdCustomerAddress($addressId);
        $addressTransfer = $this->locator->customer()->facade()->getAddress($addressTransfer);
        if ($addressTransfer) {
            return $addressTransfer->toArray();
        }

        return [];
    }

    /**
     * @return array
     */
    public function getCountryOptions()
    {
        return [
            ["value" => "1", "label" => "Germany"],
        ];
    }
}
