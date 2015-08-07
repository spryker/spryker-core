<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Form\OrderItemSplitForm;

use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature\Zed\Sales\Communication\Form\OrderItemSplitForm;
use SprykerFeature\Zed\Sales\Communication\Exception\FormNotFoundException;

class Collection
{

    const SPLIT_SUBMIT_URL = '/sales/order-item-split/split';
    const FORM_NOT_FOUND_MESSAGE = 'Form with "%d" is not set.';

    /**
     * @var SpySalesOrderItem[]
     */
    private $orderItems;

    /**
     * @var OrderItemSplitForm[]
     */
    private $forms = [];

    /**
     * @param SpySalesOrderItem[]|ObjectCollection $orderItems
     */
    public function __construct(ObjectCollection $orderItems)
    {
        $this->orderItems = $orderItems;
    }

    /**
     * @return $this
     */
    public function create()
    {
        foreach ($this->orderItems as $orderItem) {
            $form = $this->createOrderItemSplitForm()
                ->setOptions(['action' => self::SPLIT_SUBMIT_URL])
                ->setData([
                    OrderItemSplitForm::ID_ORDER_ITEM => $orderItem->getIdSalesOrderItem(),
                    OrderItemSplitForm::ID_ORDER => $orderItem->getFkSalesOrder(),
                 ])
               ;

            $this->forms[$orderItem->getIdSalesOrderItem()] = $form->createView();
        }

        return $this;
    }

    /**
     * @return OrderItemSplitForm
     */
    protected function createOrderItemSplitForm()
    {
        return new OrderItemSplitForm();
    }

    /**
     * @param int $formIndexId
     *
     * @throws FormNotFoundException
     *
     * @return OrderItemSplitForm
     */
    public function getById($formIndexId)
    {
        if (!$this->isFormSet($formIndexId)) {
            throw new FormNotFoundException(sprintf(self::FORM_NOT_FOUND_MESSAGE, $formIndexId));
        }

        return $this->forms[$formIndexId];
    }

    /**
     * @param int $idFormIndex
     *
     * @return bool
     */
    public function isFormSet($idFormIndex)
    {
        return isset($this->forms[$idFormIndex]);
    }

}
