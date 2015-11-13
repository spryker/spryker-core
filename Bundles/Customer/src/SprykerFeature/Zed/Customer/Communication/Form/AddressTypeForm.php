<?php

namespace SprykerFeature\Zed\Customer\Communication\Form;

use Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractFormType;
use Symfony\Component\Form\FormBuilderInterface;

class AddressTypeForm extends AbstractFormType
{

    const FIELD_SALUTATION = 'salutation';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_ID_CUSTOMER = 'id_customer';
    const FIELD_ID_CUSTOMER_ADDRESS = 'id_customer_address';
    const FIELD_FK_CUSTOMER = 'fk_customer';
    const FIELD_ADDRESS_1 = 'address1';
    const FIELD_ADDRESS_2 = 'address2';
    const FIELD_ADDRESS_3 = 'address3';
    const FIELD_CITY = 'city';
    const FIELD_ZIP_CODE = 'zip_code';
    const FIELD_FK_COUNTRY = 'fk_country';
    const FIELD_PHONE = 'phone';
    const FIELD_COMPANY = 'company';
    const FIELD_COMMENT = 'comment';

    /**
     * @var SpyCustomerAddressQuery
     */
    protected $customerAddressQuery;

    /**
     * @var SpyCustomerQuery
     */
    protected $customerQuery;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param SpyCustomerAddressQuery $addressQuery
     * @param SpyCustomerQuery $customerQuery
     * @param string $type
     */
    public function __construct(SpyCustomerAddressQuery $addressQuery, SpyCustomerQuery $customerQuery, $type)
    {
        $this->customerQuery = $customerQuery;
        $this->addressQuery = $addressQuery;
        $this->type = $type;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_ID_CUSTOMER_ADDRESS, 'hidden')
            ->add(self::FIELD_FK_CUSTOMER, 'hidden')
            ->add(self::FIELD_SALUTATION, 'choice', [
                'label' => 'Salutation',
                'placeholder' => 'Select one',
                'choices' => $this->getSalutationOptions(),
            ])
            ->add(self::FIELD_FIRST_NAME, 'text', [
                'label' => 'First Name',
                'constraints' => [
                    $this->getConstraints()->createConstraintRequired(),
                    $this->getConstraints()->createConstraintNotBlank(),
                    $this->getConstraints()->createConstraintLength(['max' => 100]),
                ],
            ])
            ->add(self::FIELD_LAST_NAME, 'text', [
                'label' => 'Last Name',
                'constraints' => [
                    $this->getConstraints()->createConstraintRequired(),
                    $this->getConstraints()->createConstraintNotBlank(),
                    $this->getConstraints()->createConstraintLength(['max' => 100]),
                ],
            ])
            ->add(self::FIELD_ADDRESS_1, 'text', [
                'label' => 'Address line 1',
            ])
            ->add(self::FIELD_ADDRESS_2, 'text', [
                'label' => 'Address line 2',
            ])
            ->add(self::FIELD_ADDRESS_3, 'text', [
                'label' => 'Address line 3',
            ])
            ->add(self::FIELD_CITY, 'text', [
                'label' => 'City',
            ])
            ->add(self::FIELD_ZIP_CODE, 'text', [
                'label' => 'Zip Code',
                'constraints' => [
                    $this->getConstraints()->createConstraintLength(['max' => 15]),
                ],
            ])
            ->add(self::FIELD_FK_COUNTRY, 'choice', [
                'label' => 'Country',
                'placeholder' => 'Select one',
                'choices' => $this->getCountryOptions(),
                'preferred_choices' => [
                    $this->addressQuery->useCountryQuery()
                        ->findOneByName('Germany')
                        ->getIdCountry(),
                ],
            ])
            ->add(self::FIELD_PHONE, 'text', [
                'label' => 'Phone',
            ])
            ->add(self::FIELD_COMPANY, 'text', [
                'label' => 'Company',
            ])
            ->add(self::FIELD_COMMENT, 'textarea', [
                'label' => 'Comment',
                'constraints' => [
                    $this->getConstraints()->createConstraintLength(['max' => 255]),
                ],
            ])
        ;
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

    /**
     * @return array
     */
    protected function getSalutationOptions()
    {
        $salutation = SpyCustomerTableMap::getValueSet(SpyCustomerTableMap::COL_SALUTATION);

        return $this->getEnumSet($salutation);
    }

    public function getName()
    {
        return 'customer_address';
    }

}
