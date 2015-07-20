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
    /**
     * @var SpySalesOrderItem[]
     */
    private $orderItems;

    /**
     * @var OrderItemSplitForm[]
     */
    private $forms = [];

    /**
     * Builder constructor.
     *
     * @param SpySalesOrderItem[]|ObjectCollection $orderItems
     */
    public function __construct(
        ObjectCollection $orderItems
    ) {
        $this->orderItems = $orderItems;
    }

    /**
     * @return $this
     */
    public function create()
    {
        foreach ($this->orderItems as $orderItem) {
            $form = $orderItemSplitForm = $this->getOrderItemSplitForm()
                ->init(
                    ['action' => '/sales/orderItemSplit/split'],
                    [
                        'id_order_item' => $orderItem->getIdSalesOrderItem(),
                        'id_order' => $orderItem->getFkSalesOrder()
                    ]
                );
            $this->forms[$orderItem->getIdSalesOrderItem()] = $form->createView();
        }

        return $this;
    }

    /**
     * @return OrderItemSplitForm
     */
    protected function getOrderItemSplitForm()
    {
        return new OrderItemSplitForm();
    }

    /**
     * @param $formIndexId
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
