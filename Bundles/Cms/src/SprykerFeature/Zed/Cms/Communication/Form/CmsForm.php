<?php

namespace SprykerFeature\Zed\Cms\Communication\Form;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Sales\Persistence\Propel\Base\SpySalesOrderQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class CmsForm extends AbstractForm
{

    const TEMPLATE_NAME = 'template_name';
    const URL = 'url';
    const URL_TYPE = 'url_type';


//    protected $orderQuery;
//    protected $idOrder;

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
