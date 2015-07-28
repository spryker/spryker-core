<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model\Split;

use Generated\Shared\Transfer\ItemSplitResponseTransfer;
use SprykerFeature\Zed\Sales\Persistence;
use SprykerFeature\Zed\Sales\Business\Model\Split\Validation;
use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionInterface;
use Generated\Shared\Sales\ItemSplitResponseInterface;

class OrderItem implements OrderItemInterface
{
    const SPLIT_MARKER = 'split#';
    /**
     * @var ConnectionInterface
     */
    protected $databaseConnection;

    /**
     * @var Validation\ValidatorInterface
     */
    protected $validator;

    /**
     * @var Persistence\SalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @var CalculatorInterface
     */
    protected $calculator;

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
     * @return ItemSplitResponseInterface
     * @throws \Exception
     */
    public function split($orderItemId, $quantityToSplit)
    {
        $salesOrderItem = $this->salesQueryContainer
            ->querySalesOrderItem()
            ->findOneByIdSalesOrderItem($orderItemId);

        $splitResponse = new ItemSplitResponseTransfer();
        if (false === $this->validator->isValid($salesOrderItem, $quantityToSplit)) {
            return $splitResponse
                ->setSuccess(false)
                ->setValidationMessages($this->validator->getMessages());
        }

        try {
            $this->getConnection()->beginTransaction();

            $this->copy($salesOrderItem, $quantityToSplit);
            $this->updateParentQuantity($salesOrderItem, $quantityToSplit);

            $this->getConnection()->commit();

            return $splitResponse
                ->setSuccess(true)
                ->setSuccessMessage(
                    sprintf(Validation\Messages::SPLIT_SUCCESS_MESSAGE, $salesOrderItem->getIdSalesOrderItem())
                );

        } catch (\Exception $e) {
            $this->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    protected function getConnection()
    {
        if (null === $this->databaseConnection) {
            $this->databaseConnection = Propel::getConnection();
        }

        return $this->databaseConnection;
    }

    /**
     * @param ConnectionInterface $databaseConnection
     */
    public function setDatabaseConnection(ConnectionInterface $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
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

        $copyOfSalesOrderItem->setGroupKey(self::SPLIT_MARKER . $copyOfSalesOrderItem->getGroupKey());
        $copyOfSalesOrderItem->setCreatedAt(new \DateTime());
        $copyOfSalesOrderItem->setQuantity($quantity);
        $copyOfSalesOrderItem->setLastStateChange(new \DateTime());
        $copyOfSalesOrderItem->save($this->getConnection());

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

        $copyOfOrderItemOption = $salesOrderItemOption->copy(false);

        $copyOfOrderItemOption->setCreatedAt(new \DateTime());
        $copyOfOrderItemOption->setFkSalesOrderItem($copyOfSalesOrderItem->getIdSalesOrderItem());
        $copyOfOrderItemOption->save($this->getConnection());

        return $copyOfOrderItemOption;
    }

    /**
     * @param Persistence\Propel\SpySalesOrderItem $salesOrderItem
     * @param integer $quantity
     */
    protected function updateParentQuantity(Persistence\Propel\SpySalesOrderItem $salesOrderItem, $quantity)
    {
        $quantityAmountLeft = $this->calculator->calculateQuantityAmountLeft($salesOrderItem, $quantity);

        $salesOrderItem->setQuantity($quantityAmountLeft);
        $salesOrderItem->save($this->getConnection());
    }
}
