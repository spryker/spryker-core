<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model\OrderItemSplit\Validation;

use SprykerFeature\Zed\Sales\Persistence;

class Validator implements ValidatorInterface
{
    /**
     * @var array
     */
    private $messages = [];

    /**
     * @param Persistence\Propel\SpySalesOrderItem $salesOrderItem
     * @param integer                              $quantityToSplit
     *
     * @return bool
     */
    public function isValid(Persistence\Propel\SpySalesOrderItem $salesOrderItem, $quantityToSplit)
    {
        $this->isValidQuantity($salesOrderItem, $quantityToSplit);
        $this->isBundled($salesOrderItem);
        $this->isDiscounted($salesOrderItem);
        $this->isDiscountedOption($salesOrderItem);

        return empty($this->messages);
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param Persistence\Propel\SpySalesOrderItem $salesOrderItem
     * @param integer                              $quantityToSplit
     *
     * @return bool
     */
    protected function isValidQuantity(Persistence\Propel\SpySalesOrderItem $salesOrderItem, $quantityToSplit)
    {
        if ($quantityToSplit < 1 || $salesOrderItem->getQuantity() <= $quantityToSplit) {
            $this->messages[] = Messages::VALIDATE_QUANTITY_MESSAGE;
            return false;
        }

        return true;
    }

    /**
     * @param Persistence\Propel\SpySalesOrderItem $salesOrderItem
     *
     * @return bool
     */
    protected function isBundled(Persistence\Propel\SpySalesOrderItem $salesOrderItem)
    {
        if (null !== $salesOrderItem->getFkSalesOrderItemBundle()) {
            $this->messages[] = Messages::VALIDATE_BUNDLE_MESSAGE;
            return true;
        }

        return false;
    }

    /**
     * @param Persistence\Propel\SpySalesOrderItem $salesOrderItem
     *
     * @return bool
     */
    protected function isDiscounted(Persistence\Propel\SpySalesOrderItem $salesOrderItem)
    {
        if ($salesOrderItem->countDiscounts() > 0) {
            $this->messages[] = Messages::VALIDATE_DISCOUNTED_MESSAGE;
            return true;
        }

        return false;
    }

    /**
     * @param Persistence\Propel\SpySalesOrderItem $salesOrderItem
     *
     * @return bool
     */
    protected function isDiscountedOption(Persistence\Propel\SpySalesOrderItem $salesOrderItem)
    {
        if ($salesOrderItem->countOptions() > 0) {
            foreach ($salesOrderItem->getOptions() as $orderItemOption) {
                if ($orderItemOption->countDiscounts() > 0) {
                    $this->messages[] = Messages::VALIDATE_DISCOUNTED_OPTION_MESSAGE;
                    return true;
                }
            }
        }

        return false;
    }
}
