<?php
namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Zed\Ide\FactoryAutoCompletion\SalesBusiness;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesExpense;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemOption;

class OrderItemBuilder
{

    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @var SalesBusiness
     */
    protected $factory;

    /**
     * @param LocatorLocatorInterface $locator
     * @param FactoryInterface $factory
     */
    public function __construct(LocatorLocatorInterface $locator, FactoryInterface $factory)
    {
        $this->locator = $locator;
        $this->factory = $factory;
    }

    /**
     * @var \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStatus
     */
    protected $statusEntity;

    protected function getInitialStatusEntity()
    {
        if (!isset($this->statusEntity)) {
            $this->statusEntity = $this->locator->oms()->facade()->getInitialStatusEntity();
        }
        return $this->statusEntity;
    }

    /**
     * @param OrderItem $transferItem
     * @param Order $transferOrder
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem
     */
    public function createOrderItemEntity(OrderItem $transferItem, Order $transferOrder)
    {
        $item = new SpySalesOrderItem();
        $item->fromArray($transferItem->toArray());
        $item->setStatus($this->getInitialStatusEntity());

        $this->addProcess($transferItem, $transferOrder, $item);
//        $this->addOptions($transferItem, $item);
        $this->addExpenses($transferItem, $item);

        $this->locator->salesrule()->facade()->addDiscountsToDiscountableItem($item, $transferItem->getDiscounts());

        return $item;
    }

    /**
     * @param OrderItem $transferOrderItem
     * @param Order $transferOrder
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem
     */
    protected function addProcess(
        OrderItem $transferOrderItem,
        Order $transferOrder,
        SpySalesOrderItem $orderItem
    ) {
        $processName = $this->locator->oms()->facade()->selectProcess($transferOrder);
        $process = $this->locator->oms()->facade()->getProcessEntity($processName);
        $orderItem->setProcess($process);
    }

    /**
     * @param OrderItem $transferOrderItem
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item
     * @throws \RuntimeException
     */
    protected function addOptions(OrderItem $transferOrderItem, SpySalesOrderItem $item)
    {
        foreach ($transferOrderItem->getOptions() as $option) {
            $optionEntity = $this->locator->catalog()->facade()->getProductOptionByIdentifier($option->getIdentifier());
            if (!$optionEntity) {
                throw new \RuntimeException('Option with id ' . $option->getIdentifier() . ' was not found');
            }

            $values = $option->toArray(false, false);
            // Update name and description from entity
            $values['name'] = $optionEntity->getName();
            $values['description'] = $optionEntity->getDescription();

            $entity = new SpySalesOrderItemOption();
            $entity->fromArray($values);
            $item->addOption($entity);

            $this->locator->salesrule()->facade()->addDiscountsToDiscountableItem($entity, $option->getDiscounts());
        }
    }

    /**
     * @param OrderItem $transferItem
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item
     */
    protected function addExpenses(
        OrderItem $transferItem,
        SpySalesOrderItem $item
    ) {
        foreach ($transferItem->getExpenses() as $expense) {
            $entity = new SpySalesExpense();
            $entity->fromArray($expense->toArray());
            $item->addExpense($entity);

            $this->locator->salesrule()->facade()->addDiscountsToDiscountableItem($entity, $expense->getDiscounts());
        }
    }

}
