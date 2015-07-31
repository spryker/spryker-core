<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model\Split;

use Generated\Shared\Sales\ItemSplitResponseInterface;
use Generated\Shared\Transfer\ItemSplitResponseTransfer;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Propel;
use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclGroupQuery;
use SprykerFeature\Zed\Sales\Business\Model\Split\Validation;
use SprykerFeature\Zed\Sales\Business\Model\Split\Validation\ValidatorInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemOption;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;

class OrderItem implements OrderItemInterface
{

    const SPLIT_MARKER = 'split#';

    /**
     * @var ConnectionInterface
     */
    protected $databaseConnection;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var SalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @var CalculatorInterface
     */
    protected $calculator;

    /**
     * @param ValidatorInterface $validator
     * @param SalesQueryContainerInterface $salesQueryContainer
     * @param CalculatorInterface $splitCalculator
     */
    public function __construct(
        ValidatorInterface $validator,
        SalesQueryContainerInterface $salesQueryContainer,
        CalculatorInterface $splitCalculator
    ) {
        $this->validator = $validator;
        $this->salesQueryContainer = $salesQueryContainer;
        $this->calculator = $splitCalculator;
    }

    /**
     * @param integer $idSalesOrderItem
     * @param integer $quantityToSplit
     *
     * @return ItemSplitResponseInterface
     * @throws \Exception
     */
    public function split($idSalesOrderItem, $quantityToSplit)
    {
        $salesOrderItem = $this->salesQueryContainer
            ->querySalesOrderItem()
            ->findOneByIdSalesOrderItem($idSalesOrderItem);

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
     * @param SpySalesOrderItem $salesOrderItem
     * @param integer $quantity
     */
    protected function copy(SpySalesOrderItem $salesOrderItem, $quantity)
    {
        $copyOfSalesOrderItem = $this->createSalesOrderItemCopy($salesOrderItem, $quantity);

        foreach ($salesOrderItem->getOptions() as $salesOrderItemOption) {
            $this->createOrderItemOptionCopy($salesOrderItemOption, $copyOfSalesOrderItem);
        }
    }

    /**
     * @param SpySalesOrderItem $salesOrderItem
     * @param integer $quantity
     *
     * @return SpySalesOrderItem
     */
    protected function createSalesOrderItemCopy(SpySalesOrderItem $salesOrderItem, $quantity)
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
     * @param SpySalesOrderItemOption $salesOrderItemOption
     * @param SpySalesOrderItem $copyOfSalesOrderItem
     *
     * @return SpySalesOrderItemOption
     */
    protected function createOrderItemOptionCopy(
        SpySalesOrderItemOption $salesOrderItemOption,
        SpySalesOrderItem $copyOfSalesOrderItem
    ) {

        $copyOfOrderItemOption = $salesOrderItemOption->copy(false);

        $copyOfOrderItemOption->setCreatedAt(new \DateTime());
        $copyOfOrderItemOption->setFkSalesOrderItem($copyOfSalesOrderItem->getIdSalesOrderItem());
        $copyOfOrderItemOption->save($this->getConnection());

        return $copyOfOrderItemOption;
    }

    /**
     * @param SpySalesOrderItem $salesOrderItem
     * @param integer $quantity
     */
    protected function updateParentQuantity(SpySalesOrderItem $salesOrderItem, $quantity)
    {
        $quantityAmountLeft = $this->calculator->calculateQuantityAmountLeft($salesOrderItem, $quantity);

        $salesOrderItem->setQuantity($quantityAmountLeft);
        $salesOrderItem->save($this->getConnection());
    }
}
