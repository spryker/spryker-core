<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Form;

use Generated\Shared\Transfer\CustomerAddressTransfer;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;

use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerAddressQuery;

use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Length;

use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;

class AddressForm extends AbstractForm
{

    const UPDATE = 'update';
    /**
     * @var SpyCustomerAddressQuery
     */
    protected $customerAddressQuery;

    /**
     * @var SpyCustomerQuery
     */
    protected $customerQuery;

    /**
     * @var
     */
    protected $type;

    /**
     * @param SpyCustomerAddressQuery $addressQuery
     */
    public function __construct(SpyCustomerAddressQuery $addressQuery, SpyCustomerQuery $customerQuery, $type)
    {
        $this->customerQuery = $customerQuery;
        $this->addressQuery = $addressQuery;
        $this->type = $type;
    }

    /**
     * @return $this
     */
    public function buildFormFields()
    {
        return $this->addHidden(
            'id_customer_address',
            [
                'constraints' => [],
            ]
        )
            ->addHidden(
                'fk_customer',
                [
                    'constraints' => [],
                ]
            )
            ->addChoice(
                'salutation',
                [
                    'label'       => 'Salutation',
                    'placeholder' => 'Select one',
                    'choices'     => $this->getSalutationOptions(),
                ]
            )
            ->addText(
                'first_name',
                [
                    'label'       => 'First Name',
                    'constraints' => [
                        new Required(),
                        new NotBlank(),
                        new Length(['max' => 100]),
                    ],
                ]
            )
            ->addText(
                'last_name',
                [
                    'label'       => 'Last Name',
                    'constraints' => [
                        new Required(),
                        new NotBlank(),
                        new Length(['max' => 100]),
                    ],
                ]
            )
            ->addText(
                'address1',
                [
                    'label'       => 'Address line 1',
                    'constraints' => [
                    ],
                ]
            )
            ->addText(
                'address2',
                [
                    'label'       => 'Address line 2',
                    'constraints' => [
                    ],
                ]
            )
            ->addText(
                'address3',
                [
                    'label'       => 'Address line 3',
                    'constraints' => [
                    ],
                ]
            )
            ->addText(
                'city',
                [
                    'label'       => 'City',
                    'constraints' => [
                    ],
                ]
            )
            ->addText(
                'zip_code',
                [
                    'label'       => 'Zip Code',
                    'constraints' => [
                        new Length(['max' => 15]),
                    ],
                ]
            )
            ->addChoice(
                'fk_country',
                [
                    'label'             => 'Country',
                    'placeholder'       => 'Select one',
                    'choices'           => $this->getCountryOptions(),
                    'preferred_choices' => [
                        $this->addressQuery->useCountryQuery()->findOneByName('Germany')->getIdCountry(),
                    ],
                ]
            )
            ->addText(
                'phone',
                [
                    'label' => 'Phone',
                ]
            )
            ->addText(
                'company',
                [
                    'label'       => 'Company',
                    'constraints' => [
                    ],
                ]
            )
            ->addTextarea(
                'comment',
                [
                    'label'       => 'Comment',
                    'constraints' => [
                        new Length(['max' => 255]),
                    ],
                ]
            )
            ->addSubmit(
                'submit',
                [
                    'label' => (self::UPDATE === $this->type ? 'Update' : 'Add'),
                    'attr'  => [
                        'class' => 'btn btn-primary',
                    ],
                ]
            );
    }

    /**
     * @return array
     */
    public function populateFormFields()
    {
        $result = [];

        $idCustomer = $this->request->get('id_customer');

        if (false === is_null($idCustomer)) {
            $customerDetailEntity = $this
                ->customerQuery
                ->findOneByIdCustomer($idCustomer);

            $customerDetails = $customerDetailEntity->toArray();
        }

        $idCustomerAddress = $this->request->get('id_customer_address');
        if (false === is_null($idCustomerAddress)) {
            $addressDetailEntity = $this
                ->addressQuery
                ->findOneByIdCustomerAddress($idCustomerAddress);

            $result = $addressDetailEntity->toArray();
        }

        if (empty($result['salutation'])) {
            $result['salutation'] = !empty($customerDetails['salutation']) ? $customerDetails['salutation'] : false;
        }

        if (!empty($result['salutation'])) {
            // key: value => value: key
            $salutations = array_flip($this->getSalutationOptions());

            if (isset($salutations[$result['salutation']])) {
                $result['salutation'] = $salutations[$result['salutation']];
            }
        }

        if (empty($result['first_name'])) {
            $result['first_name'] = !empty($customerDetails['first_name']) ? $customerDetails['first_name'] : '';
        }

        if (empty($result['last_name'])) {
            $result['last_name'] = !empty($customerDetails['last_name']) ? $customerDetails['last_name'] : '';
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getSalutationOptions()
    {
        return [
            0 => SpyCustomerTableMap::COL_SALUTATION_MR,
            1 => SpyCustomerTableMap::COL_SALUTATION_MRS,
            2 => SpyCustomerTableMap::COL_SALUTATION_DR,
        ];
    }

    /**
     * @return array
     */
    public function getCountryOptions()
    {
        $countries = $this->addressQuery
            ->useCountryQuery()
            ->find();

        $result = [];
        if (!empty($countries)) {
            foreach ($countries->getData() as $country) {
                $result[$country->getIdCountry()] = $country->getName();
            }
        }

        return $result;
    }

}
