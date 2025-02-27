<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Validator\Rule\SalesOrderItem;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\OrderConditionsTransfer;
use Generated\Shared\Transfer\OrderCriteriaTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\Sales\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class SalesOrderItemRelationValidatorRule implements SalesOrderItemValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SALES_ORDER_ENTITY_NOT_FOUND = 'sales.validation.sales_order_entity_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ITEMS_NOT_FROM_SAME_ORDER = 'sales.validation.items_not_from_same_order';

    /**
     * @param \Spryker\Zed\Sales\Business\Validator\Util\ErrorAdderInterface $errorAdder
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     */
    public function __construct(protected ErrorAdderInterface $errorAdder, protected SalesRepositoryInterface $salesRepository)
    {
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $itemTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        $orderTransfer = $this->findOrder($itemTransfers);

        if (!$orderTransfer) {
            return $this->errorAdder->addError(
                $errorCollectionTransfer,
                null,
                static::GLOSSARY_KEY_VALIDATION_SALES_ORDER_ENTITY_NOT_FOUND,
            );
        }

        foreach ($itemTransfers as $entityIdentifier => $itemTransfer) {
            if ($itemTransfer->getFkSalesOrderOrFail() !== $orderTransfer->getIdSalesOrderOrFail()) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_ITEMS_NOT_FROM_SAME_ORDER,
                );
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    protected function findOrder(ArrayObject $itemTransfers): ?OrderTransfer
    {
        $orderConditionsTransfer = (new OrderConditionsTransfer())
            ->setWithOrderExpanderPlugins(false)
            ->addIdSalesOrder($itemTransfers->offsetGet(0)->getFkSalesOrderOrFail());

        return $this->salesRepository
            ->getOrderCollection((new OrderCriteriaTransfer())->setOrderConditions($orderConditionsTransfer))
            ->getOrders()
            ->getIterator()
            ->current();
    }
}
