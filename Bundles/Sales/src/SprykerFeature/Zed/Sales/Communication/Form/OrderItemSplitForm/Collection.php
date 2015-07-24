<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Form\OrderItemSplitForm;

use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature\Zed\Sales\Communication\Form\OrderItemSplitForm;

class Collection
{
    const SPLIT_SUBMIT_URL = '/sales/order-item-split/split';

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
                ->init(
                    [
                        'action' => self::SPLIT_SUBMIT_URL
                    ],
                    [
                        OrderItemSplitForm::ID_ORDER_ITEM=> $orderItem->getIdSalesOrderItem(),
                        OrderItemSplitForm::ID_ORDER => $orderItem->getFkSalesOrder()
                    ]
                );
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
     * @param integer $formIndexId
     *
     * @return OrderItemSplitForm
     */
    public function getById($formIndexId)
    {
        if (!$this->isFormSet($formIndexId)) {
            throw new \InvalidArgumentException(sprintf('Form with "%d" is not set.', $formIndexId));
        }

        return $this->forms[$formIndexId];
    }

    /**
     * @param integer $formIndexId
     *
     * @return bool
     */
    public function isFormSet($formIndexId)
    {
        return isset($this->forms[$formIndexId]);
    }
}
