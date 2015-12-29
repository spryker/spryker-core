<?php

namespace Spryker\Zed\Sales\Communication\Form;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Spryker\Shared\Gui\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;

class AddressForm extends AbstractForm
{

    const SALUTATION = 'salutation';
    const FIRST_NAME = 'first_name';
    const MIDDLE_NAME = 'middle_name';
    const LAST_NAME = 'last_name';
    const EMAIL = 'email';
    const ADDRESS_1 = 'address1';
    const ADDRESS_2 = 'address2';
    const ADDRESS_3 = 'address3';
    const COMPANY = 'company';
    const CITY = 'city';
    const ZIP_CODE = 'zip_code';
    const PO_BOX = 'po_box';
    const PHONE = 'phone';
    const CELL_PHONE = 'cell_phone';
    const DESCRIPTION = 'description';
    const COMMENT = 'comment';

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
            ->add(self::SALUTATION, 'choice', [
                'label' => 'Salutation',
                'placeholder' => '-select-',
                'choices' => $this->getSalutationOptions(),
            ])
            ->add(self::FIRST_NAME, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::MIDDLE_NAME, 'text')
            ->add(self::LAST_NAME, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::EMAIL, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                    $this->getConstraints()->createConstraintEmail(),
                ],
            ])
            ->add(self::ADDRESS_1, 'text')
            ->add(self::COMPANY, 'text')
            ->add(self::CITY, 'text')
            ->add(self::ZIP_CODE, 'text')
            ->add(self::PO_BOX, 'text')
            ->add(self::PHONE, 'text')
            ->add(self::CELL_PHONE, 'text')
            ->add(self::DESCRIPTION, 'text')
            ->add(self::COMMENT, 'textarea');
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
            self::FIRST_NAME => $address->getFirstName(),
            self::MIDDLE_NAME => $address->getMiddleName(),
            self::LAST_NAME => $address->getLastName(),
            self::EMAIL => $address->getEmail(),
            self::ADDRESS_1 => $address->getAddress1(),
            self::COMPANY => $address->getCompany(),
            self::CITY => $address->getCity(),
            self::ZIP_CODE => $address->getZipCode(),
            self::PO_BOX => $address->getPoBox(),
            self::PHONE => $address->getPhone(),
            self::CELL_PHONE => $address->getCellPhone(),
            self::DESCRIPTION => $address->getDescription(),
            self::COMMENT => $address->getComment(),
            self::SALUTATION => $address->getSalutation(),
        ];
    }

}
