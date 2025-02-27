<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Validator\Rule\SalesOrderItem;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\Sales\Business\Validator\Util\ErrorAdderInterface;

class DuplicationSalesOrderItemValidatorRule implements SalesOrderItemValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SALES_ORDER_ITEM_ENTITY_DUPLICATED = 'sales.validation.sales_order_item_entity_duplicated';

    /**
     * @param \Spryker\Zed\Sales\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(protected ErrorAdderInterface $errorAdder)
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

        $prevSalesOrderItemIds = [];
        foreach ($itemTransfers as $entityIdentifier => $itemTransfer) {
            $idSalesOrderItem = $itemTransfer->getIdSalesOrderItemOrFail();
            if (in_array($idSalesOrderItem, $prevSalesOrderItemIds, true)) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_SALES_ORDER_ITEM_ENTITY_DUPLICATED,
                );
            }

            $prevSalesOrderItemIds[] = $idSalesOrderItem;
        }

        return $errorCollectionTransfer;
    }
}
