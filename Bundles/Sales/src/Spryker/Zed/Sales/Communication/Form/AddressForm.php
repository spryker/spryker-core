<?php

namespace Spryker\Zed\Sales\Communication\Form;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Spryker\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;

class AddressForm extends AbstractForm
{

    const FIELD_SALUTATION = 'salutation';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_MIDDLE_NAME = 'middle_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_EMAIL = 'email';
    const FIELD_ADDRESS_1 = 'address1';
    const ADDRESS_2 = 'address2';
    const ADDRESS_3 = 'address3';
    const FIELD_COMPANY = 'company';
    const FIELD_CITY = 'city';
    const FIELD_ZIP_CODE = 'zip_code';
    const FIELD_PO_BOX = 'po_box';
    const FIELD_PHONE = 'phone';
    const FIELD_CELL_PHONE = 'cell_phone';
    const FIELD_DESCRIPTION = 'description';
    const FIELD_COMMENT = 'comment';

    const SUBMIT = 'submit';

    /**
     * @var SpySalesOrderAddressQuery
     */
    protected $addressQuery;

    /**
     * @param SpySalesOrderAddressQuery $addressQuery
     */
    public function __construct(SpySalesOrderAddressQuery $addressQuery)
    {
        $this->addressQuery = $addressQuery;
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
        return 'address';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_SALUTATION, 'choice', [
                'label' => 'Salutation',
                'placeholder' => '-select-',
                'choices' => $this->getSalutationOptions(),
            ])
            ->add(self::FIELD_FIRST_NAME, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::FIELD_MIDDLE_NAME, 'text')
            ->add(self::FIELD_LAST_NAME, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::FIELD_EMAIL, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                    $this->getConstraints()->createConstraintEmail(),
                ],
            ])
            ->add(self::FIELD_ADDRESS_1, 'text')
            ->add(self::FIELD_COMPANY, 'text')
            ->add(self::FIELD_CITY, 'text')
            ->add(self::FIELD_ZIP_CODE, 'text')
            ->add(self::FIELD_PO_BOX, 'text')
            ->add(self::FIELD_PHONE, 'text')
            ->add(self::FIELD_CELL_PHONE, 'text')
            ->add(self::FIELD_DESCRIPTION, 'text')
            ->add(self::FIELD_COMMENT, 'textarea');
    }

    /**
     * @return array
     */
    protected function getSalutationOptions()
    {
        return [
            SpyCustomerTableMap::COL_SALUTATION_MR => SpyCustomerTableMap::COL_SALUTATION_MR,
            SpyCustomerTableMap::COL_SALUTATION_MRS => SpyCustomerTableMap::COL_SALUTATION_MRS,
            SpyCustomerTableMap::COL_SALUTATION_DR => SpyCustomerTableMap::COL_SALUTATION_DR,
        ];
    }

    /**
     * @return array
     */
    public function populateFormFields()
    {
        $address = $this->addressQuery->findOne();

        return [
            self::FIELD_FIRST_NAME => $address->getFirstName(),
            self::FIELD_MIDDLE_NAME => $address->getMiddleName(),
            self::FIELD_LAST_NAME => $address->getLastName(),
            self::FIELD_EMAIL => $address->getEmail(),
            self::FIELD_ADDRESS_1 => $address->getAddress1(),
            self::FIELD_COMPANY => $address->getCompany(),
            self::FIELD_CITY => $address->getCity(),
            self::FIELD_ZIP_CODE => $address->getZipCode(),
            self::FIELD_PO_BOX => $address->getPoBox(),
            self::FIELD_PHONE => $address->getPhone(),
            self::FIELD_CELL_PHONE => $address->getCellPhone(),
            self::FIELD_DESCRIPTION => $address->getDescription(),
            self::FIELD_COMMENT => $address->getComment(),
            self::FIELD_SALUTATION => $address->getSalutation(),
        ];
    }

}
