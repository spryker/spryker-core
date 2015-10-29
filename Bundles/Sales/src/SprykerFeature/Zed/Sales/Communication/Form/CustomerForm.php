<?php

namespace SprykerFeature\Zed\Sales\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class CustomerForm extends AbstractForm
{

    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const SALUTATION = 'salutation';
    const EMAIL = 'email';
    const SUBMIT = 'submit';

    protected $orderQuery;
    protected $idOrder;

    /**
     * @param SpySalesOrderQuery $orderQuery
     */
    public function __construct(SpySalesOrderQuery $orderQuery)
    {
        $this->orderQuery = $orderQuery;
    }

    /**
     * @return CustomerForm
     */
    protected function buildFormFields()
    {
        return $this->addChoice(self::SALUTATION, [
            'label' => 'Salutation',
            'placeholder' => '-select-',
            'choices' => $this->getSalutationOptions(),
        ])
            ->addText(self::FIRST_NAME, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->addText(self::LAST_NAME, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->addText(self::EMAIL, [
                'constraints' => [
                    new Email(),
                ],
            ])
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
        $order = $this->orderQuery->findOne();

        return [
            self::FIRST_NAME => $order->getFirstName(),
            self::LAST_NAME => $order->getLastName(),
            self::SALUTATION => $order->getSalutation(),
            self::EMAIL => $order->getEmail(),
        ];
    }

}
