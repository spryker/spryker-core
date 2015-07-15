<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Form;

use Generated\Shared\Transfer\CustomerAddressTransfer;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;

use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Length;

class AddressForm extends AbstractForm
{
    /**
     * @var SpyCustomerAddressQueryQuery
     */
    protected $customerAddressQuery;

    /**
     * @param SpyCustomerQuery $customerQuery
     */
    public function __construct(SpyCustomerAddressQuery $customerAddressQuery)
    {
        $this->customerQuery = $customerAddressQuery;
    }

    /**
     * @return $this
     */
    public function buildFormFields()
    {

        return $this->addHidden(
                'id_customer_address',
                []
            )
            ->addHidden(
                'fk_customer',
                []
            )
            ->addChoice(
                'salutation',
                [
                    'label' => 'Salutation',
                    'constraints' => []
                ]
            )
            ->addText(
                'first_name',
                [
                    'label' => 'First Name',
                    'constraints' => [
                        new Required(),
                        new NotBlank(),
                        new Length(['max' => 100])
                    ]
                ]
            )
            ->addText(
                'last_name',
                [
                    'label' => 'Last Name',
                    'constraints' => [
                        new Required(),
                        new NotBlank(),
                        new Length(['max' => 100])
                    ]
                ]
            )
            ->addText(
                'address1',
                [
                    'label' => 'Address line 1',
                    'constraints' => [
                    ]
                ]
            )
            ->addText(
                'address2',
                [
                    'label' => 'Address line 2',
                    'constraints' => [
                    ]
                ]
            )
            ->addText(
                'address3',
                [
                    'label' => 'Address line 3',
                    'constraints' => [
                    ]
                ]
            )
            ->addText(
                'company',
                [
                    'label' => 'Company',
                    'constraints' => [
                    ]
                ]
            )
            ->addText(
                'city',
                [
                    'label' => 'City',
                    'constraints' => [
                    ]
                ]
            )
            ->addText(
                'zip_code',
                [
                    'label' => 'Zip Code',
                    'constraints' => [
                        new Length(['max' => 15])
                    ]
                ]
            )
            ->addChoice(
                'fk_country',
                [
                    'label' => 'Country',
                    'constraints' => [
                        'choices' => $this->getCountryOptions(),
                    ]
                ]
            )
            ->addText(
                'phone',
                [
                    'label' => 'Phone',
                ]
            );
    }

    /**
     * @return array
     */
    public function populateFormFields()
    {
        $result = [];

        $idCustomerAddress = $this->request->get('id_customer_address');
        if (false === is_null($idCustomerAddress)) {
            $customerAddressDetailEntity = $this
                ->customerAddressQuery
                ->findOneByIdCustomerAddress($idCustomerAddress);

            $result = $customerAddressDetailEntity->toArray();
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getCountryOptions()
    {
        return [
            [1 => 'Germany'],
        ];
    }

}
