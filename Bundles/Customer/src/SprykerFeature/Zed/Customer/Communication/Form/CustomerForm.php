<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Form;

use Generated\Shared\Transfer\CustomerTransfer;
use Symfony\Component\Validator\Constraints;
use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;

class CustomerForm extends AbstractForm
{

    /**
     */
    public function addFormFields()
    {
        $this->addField('id_customer')
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'integer',
                ]),
            ])
        ;

        $this->addField('email')
            ->setConstraints([
                new Constraints\Required([
                    new Constraints\Type([
                        'type' => 'string',
                    ]),
                    new Constraints\NotBlank(),
                ]),
            ])
        ;

        $this->addField('salutation')
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'string',
                ]),
            ])
        ;

        $this->addField('first_name')
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'string',
                ]),
            ])
        ;

        $this->addField('last_name')
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'string',
                ]),
            ])
        ;

        $this->addField('gender')
            ->setAccepts($this->getGenderOptions())
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'integer',
                ]),
                new Constraints\Choice([
                    'choices' => array_column($this->getGenderOptions(), 'value'),
                ]),
            ])
            ->setValueHook(function ($value) {
                return $value ? (int) $value : null;
            })
        ;
    }

    /**
     * @return array
     */
    public function getDefaultData()
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($this->stateContainer->getRequestValue('id'));
        $customerTransfer = $this->getLocator()->customer()->facade()->getCustomer($customerTransfer);

        return $customerTransfer->toArray();
    }

    /**
     * @return array
     */
    protected function getGenderOptions()
    {
        return [
            ['value' => 0, 'label' => 'customer.profile.gender.unknown'],
            ['value' => 1, 'label' => 'customer.profile.gender.male'],
            ['value' => 2, 'label' => 'customer.profile.gender.female'],
        ];
    }

}
