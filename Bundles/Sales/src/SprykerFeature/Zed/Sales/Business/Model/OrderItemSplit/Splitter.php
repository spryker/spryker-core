<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model\OrderItemSplit;

use Generated\Shared\Transfer\SplitResponseTransfer;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Map\TableMap;
use SprykerFeature\Zed\Sales\Persistence;
use SprykerFeature\Zed\Sales\Business\Model\OrderItemSplit\Validation;
use Propel\Runtime\Propel;
use Generated\Shared\Sales\SplitResponseInterface;

class Splitter
{
    /**
     * @var Validation\ValidatorInterface
     */
    private $validator;

    /**
     * @var Persistence\SalesQueryContainerInterface
     */
    private $salesQueryContainer;

    /**
     * @var CalculatorInterface
     */
    private $calculator;

    /**
     * @param Validation\ValidatorInterface $validator
     * @param Persistence\SalesQueryContainerInterface $salesQueryContainer
     * @param CalculatorInterface $splitCalculator
     */
    public function __construct(
        Validation\ValidatorInterface $validator,
        Persistence\SalesQueryContainerInterface $salesQueryContainer,
        CalculatorInterface $splitCalculator
    ) {
        $this->validator = $validator;
        $this->salesQueryContainer = $salesQueryContainer;
        $this->calculator = $splitCalculator;
    }

    /**
     * @param integer $orderItemId
     * @param integer $quantityToSplit
     *
     * @return SplitResponseInterface
     * @throws \Exception
     */
    public function split($orderItemId, $quantityToSplit)
    {
        $salesOrderItem = $this->salesQueryContainer
            ->querySalesOrderItem()
            ->findOneByIdSalesOrderItem($orderItemId);

        $splitResponse = new SplitResponseTransfer();
        if (false === $this->validator->isValid($salesOrderItem, $quantityToSplit)) {
            return $splitResponse
                ->setSuccess(false)
                ->setValidationMessages($this->validator->getValidationMessages());
        }

        try {
            Propel::getConnection()->beginTransaction();

            $this->copy($salesOrderItem, $quantityToSplit);
            $this->updateParentQuantity($salesOrderItem, $quantityToSplit);

            Propel::getConnection()->commit();

            return $splitResponse->setSuccess(true);
        } catch (\Exception $e) {
            Propel::getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param Persistence\Propel\SpySalesOrderItem $salesOrderItem
     * @param integer $quantity
     */
    protected function copy(Persistence\Propel\SpySalesOrderItem $salesOrderItem, $quantity)
    {
        $copyOfSalesOrderItem = $this->createSalesOrderItemCopy($salesOrderItem, $quantity);

        foreach ($salesOrderItem->getOptions() as $salesOrderItemOption) {
            $this->createOrderItemOptionCopy($salesOrderItemOption, $copyOfSalesOrderItem);
        }
    }

    /**
     * @param Persistence\Propel\SpySalesOrderItem $salesOrderItem
     * @param integer $quantity
     *
     * @return Persistence\Propel\SpySalesOrderItem
     */
    protected function createSalesOrderItemCopy(Persistence\Propel\SpySalesOrderItem $salesOrderItem, $quantity)
    {
        $copyOfSalesOrderItem = $salesOrderItem->copy(false);

        $copyOfSalesOrderItem->setCreatedAt(new \DateTime());
        $copyOfSalesOrderItem->setQuantity($quantity);
        $copyOfSalesOrderItem->setLastStateChange(new \DateTime());
        $copyOfSalesOrderItem->save();

        return $copyOfSalesOrderItem;
    }

    /**
     * @param Persistence\Propel\SpySalesOrderItemOption $salesOrderItemOption
     * @param Persistence\Propel\SpySalesOrderItem $copyOfSalesOrderItem
     *
     * @return Persistence\Propel\SpySalesOrderItemOption
     */
    protected function createOrderItemOptionCopy(
        Persistence\Propel\SpySalesOrderItemOption $salesOrderItemOption,
        Persistence\Propel\SpySalesOrderItem $copyOfSalesOrderItem
    ) {

        $splitOrderItemOption = $salesOrderItemOption->copy(false);

        $splitOrderItemOption->setCreatedAt(new \DateTime());
        $splitOrderItemOption->setFkSalesOrderItem($copyOfSalesOrderItem->getIdSalesOrderItem());
        $splitOrderItemOption->save();

        return $splitOrderItemOption;
    }

    /**
     * @param Persistence\Propel\SpySalesOrderItem $salesOrderItem
     * @param integer $quantity
     */
    protected function updateParentQuantity(Persistence\Propel\SpySalesOrderItem $salesOrderItem, $quantity)
    {
        $quantityAmountLeft = $this->calculator->calculateQuantityAmountLeft($salesOrderItem, $quantity);

        $salesOrderItem->setQuantity($quantityAmountLeft);
        $salesOrderItem->save();
    }
}
