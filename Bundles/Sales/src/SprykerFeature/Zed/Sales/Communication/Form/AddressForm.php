<?php

namespace SprykerFeature\Zed\Sales\Communication\Form;

use Generated\Shared\Transfer\AddressTransfer;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddressQuery;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddressForm extends AbstractForm
{

    const SALUTATION = 'salutation';
    const FIRST_NAME = 'first_name';
    const MIDDLE_NAME = 'middle_name';
    const LAST_NAME = 'last_name';
    const EMAIL = 'email';
    const ADDRESS_1 = 'address_1';
    const ADDRESS_2 = 'address_2';
    const ADDRESS_3 = 'address_3';
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
    private $addressQuery;

    /**
     * @var int
     */
    private $idOrder;

    /**
     * @param SpySalesOrderAddressQuery $addressQuery
     */
    public function __construct(SpySalesOrderAddressQuery $addressQuery)
    {
        $this->addressQuery = $addressQuery;
//        $this->setDefaultDataType(new AddressTransfer());
    }

    /**
     * @return CustomerForm
     */
    protected function buildFormFields()
    {
        return $this
            ->addChoice(self::SALUTATION, [
                'label' => 'Salutation',
                'placeholder' => '-select-',
                'choices' => $this->getSalutationOptions(),
            ])
            ->addText(self::FIRST_NAME, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->addText(self::MIDDLE_NAME)
            ->addText(self::LAST_NAME, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->addText(self::EMAIL, [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
            ])
            ->addText(self::ADDRESS_1)
            ->addText(self::COMPANY)
            ->addText(self::CITY)
            ->addText(self::ZIP_CODE)
            ->addText(self::PO_BOX)
            ->addText(self::PHONE)
            ->addText(self::CELL_PHONE)
            ->addText(self::DESCRIPTION)
            ->addTextarea(self::COMMENT)
        ;
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
    protected function populateFormFields()
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
