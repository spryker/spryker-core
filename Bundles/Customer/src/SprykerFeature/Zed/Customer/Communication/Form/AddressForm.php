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
    const SALUTATION = 'salutation';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const ID_CUSTOMER = 'id_customer';
    const ID_CUSTOMER_ADDRESS = 'id_customer_address';
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
        return $this->addHidden(self::ID_CUSTOMER_ADDRESS)
            ->addHidden('fk_customer')
            ->addChoice(self::SALUTATION, [
                    'label' => 'Salutation',
                    'placeholder' => 'Select one',
                    'choices' => $this->getSalutationOptions(),
                ])
            ->addText(self::FIRST_NAME, [
                    'label' => 'First Name',
                    'constraints' => [
                        new Required(),
                        new NotBlank(),
                        new Length(['max' => 100]),
                    ],
                ])
            ->addText(self::LAST_NAME, [
                    'label' => 'Last Name',
                    'constraints' => [
                        new Required(),
                        new NotBlank(),
                        new Length(['max' => 100]),
                    ],
                ])
            ->addText('address1', [
                    'label' => 'Address line 1',
                ])
            ->addText('address2', [
                    'label' => 'Address line 2',
                ])
            ->addText('address3', [
                    'label' => 'Address line 3',
                ])
            ->addText('city', [
                    'label' => 'City',
                ])
            ->addText('zip_code', [
                    'label' => 'Zip Code',
                    'constraints' => [
                        new Length(['max' => 15]),
                    ],
                ])
            ->addChoice('fk_country', [
                    'label' => 'Country',
                    'placeholder' => 'Select one',
                    'choices' => $this->getCountryOptions(),
                    'preferred_choices' => [
                        $this->addressQuery->useCountryQuery()
                            ->findOneByName('Germany')
                            ->getIdCountry(),
                    ],
                ])
            ->addText('phone', [
                    'label' => 'Phone',
                ])
            ->addText('company', [
                    'label' => 'Company',
                ])
            ->addTextarea('comment', [
                    'label' => 'Comment',
                    'constraints' => [
                        new Length(['max' => 255]),
                    ],
                ])
            ;
    }

    /**
     * @return array
     */
    public function populateFormFields()
    {
        $result = [];

        $idCustomer = $this->request->get(self::ID_CUSTOMER);
        if (false === is_null($idCustomer)) {
            $customerDetailEntity = $this->customerQuery->findOneByIdCustomer($idCustomer);
            $customerDetails = $customerDetailEntity->toArray();
        }

        $idCustomerAddress = $this->request->get(self::ID_CUSTOMER_ADDRESS);
        if (false === is_null($idCustomerAddress)) {
            $addressDetailEntity = $this->addressQuery->findOneByIdCustomerAddress($idCustomerAddress);
            $result = $addressDetailEntity->toArray();
        }

        if (true === empty($result[self::SALUTATION])) {
            $result[self::SALUTATION] = !empty($customerDetails[self::SALUTATION]) ? $customerDetails[self::SALUTATION] : false;
        }

        if (false === empty($result[self::SALUTATION])) {
            $salutations = array_flip($this->getSalutationOptions());

            if (true === isset($salutations[$result[self::SALUTATION]])) {
                $result[self::SALUTATION] = $salutations[$result[self::SALUTATION]];
            }
        }

        if (true === empty($result[self::FIRST_NAME])) {
            $result[self::FIRST_NAME] = !empty($customerDetails[self::FIRST_NAME]) ? $customerDetails[self::FIRST_NAME] : '';
        }

        if (true === empty($result[self::LAST_NAME])) {
            $result[self::LAST_NAME] = !empty($customerDetails[self::LAST_NAME]) ? $customerDetails[self::LAST_NAME] : '';
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getSalutationOptions()
    {
        return [
            SpyCustomerTableMap::COL_SALUTATION_MR,
            SpyCustomerTableMap::COL_SALUTATION_MRS,
            SpyCustomerTableMap::COL_SALUTATION_DR,
        ];
    }

    /**
     * @return array
     */
    public function getCountryOptions()
    {
        $countries = $this->addressQuery->useCountryQuery()
            ->find()
        ;

        $result = [];
        if (false === empty($countries)) {
            foreach ($countries->getData() as $country) {
                $result[$country->getIdCountry()] = $country->getName();
            }
        }

        return $result;
    }

}
