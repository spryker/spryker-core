<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Validator\Rule\SalesOrderItem;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\Sales\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\Sales\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class ExistenceSalesOrderItemValidatorRule implements SalesOrderItemValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SALES_ORDER_ITEM_ENTITY_NOT_FOUND = 'sales.validation.sales_order_item_entity_not_found';

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
        $salesOrderItems = $this->salesRepository->getSalesOrderItemsByOrderIds(
            [$itemTransfers->offsetGet(0)->getFkSalesOrderOrFail()],
        );
        $persistedSalesOrderItemIds = $this->extractSalesOrderItemIds($salesOrderItems);

        foreach ($itemTransfers as $entityIdentifier => $itemTransfer) {
            if (!in_array($itemTransfer->getIdSalesOrderItemOrFail(), $persistedSalesOrderItemIds, true)) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_SALES_ORDER_ITEM_ENTITY_NOT_FOUND,
                );
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $salesOrderItems
     *
     * @return list<int>
     */
    protected function extractSalesOrderItemIds(array $salesOrderItems): array
    {
        $salesOrderItemIds = [];
        foreach ($salesOrderItems as $salesOrderItem) {
            $salesOrderItemIds[] = $salesOrderItem->getIdSalesOrderItemOrFail();
        }

        return $salesOrderItemIds;
    }
}
