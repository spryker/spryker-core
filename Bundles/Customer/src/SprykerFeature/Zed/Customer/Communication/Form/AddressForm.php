<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Form;

use Generated\Shared\Transfer\CustomerAddressTransfer;
use Symfony\Component\Validator\Constraints;
use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;

class AddressForm extends AbstractForm
{

    public function addFormFields()
    {
        $this->addField('id_customer_address')
            ->setConstraints([
                new Constraints\Required([
                    new Constraints\Type([
                        'type' => 'int',
                    ]),
                ]),
            ])
        ;

        $this->addField('fk_customer')
            ->setConstraints([
                new Constraints\Required([
                    new Constraints\Type([
                        'type' => 'int',
                    ]),
                ]),
            ])
        ;

        $this->addField('first_name')
            ->setConstraints([
                new Constraints\Required([
                    new Constraints\Type([
                        'type' => 'string',
                    ]),
                    new Constraints\NotBlank(),
                ]),
            ])
        ;

        $this->addField('last_name')
            ->setConstraints([
                new Constraints\Required([
                    new Constraints\Type([
                        'type' => 'string',
                    ]),
                    new Constraints\NotBlank(),
                ]),
            ])
        ;

        $this->addField('company')
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'string',
                ]),
            ])
        ;

        $this->addField('address1')
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'string',
                ]),
            ])
        ;

        $this->addField('address2')
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'string',
                ]),
            ])
        ;

        $this->addField('zip_code')
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'string',
                ]),
            ])
        ;

        $this->addField('city')
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'string',
                ]),
                new Constraints\NotBlank(),
            ])
        ;

        $this->addField('fk_country')
            ->setAccepts($this->getCountryOptions())
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'string',
                ]),
                new Constraints\Choice([
                    'choices' => array_column($this->getCountryOptions(), 'value'),
                ]),
            ])
            ->setValueHook(function ($value) {
                return $value ? (int) $value : null;
            })
        ;

        $this->addField('state')
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'string',
                ]),
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

        $addressTransfer = new CustomerAddressTransfer();
        $addressTransfer->setIdCustomerAddress($addressId);
        $addressTransfer = $this->getLocator()->customer()->facade()->getAddress($addressTransfer);
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
            ['value' => '1', 'label' => 'Germany'],
        ];
    }

}
