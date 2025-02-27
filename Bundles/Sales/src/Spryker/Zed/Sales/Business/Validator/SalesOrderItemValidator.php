<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Spryker\Zed\Sales\Business\Validator\Rule\SalesOrderItem\SalesOrderItemValidatorRuleInterface;
use Spryker\Zed\Sales\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;

class SalesOrderItemValidator implements SalesOrderItemValidatorInterface
{
    /**
     * @param list<\Spryker\Zed\Sales\Business\Validator\Rule\SalesOrderItem\SalesOrderItemValidatorRuleInterface> $salesOrderItemValidatorRules
     */
    public function __construct(protected array $salesOrderItemValidatorRules)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function validate(
        SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        foreach ($this->salesOrderItemValidatorRules as $salesOrderItemValidatorRule) {
            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $items */
            $items = $salesOrderItemCollectionResponseTransfer->getItems();
            $errorCollectionTransfer = $salesOrderItemValidatorRule->validate($items);

            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers */
            $initialErrorTransfers = $salesOrderItemCollectionResponseTransfer->getErrors();

            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers */
            $postValidationErrorTransfers = $errorCollectionTransfer->getErrors();

            $salesOrderItemCollectionResponseTransfer = $this->mergeErrors(
                $salesOrderItemCollectionResponseTransfer,
                $errorCollectionTransfer,
            );

            if ($this->isValidationTerminated($salesOrderItemValidatorRule, $initialErrorTransfers, $postValidationErrorTransfers)) {
                break;
            }
        }

        return $salesOrderItemCollectionResponseTransfer;
    }

    /**
     * @param \Spryker\Zed\Sales\Business\Validator\Rule\SalesOrderItem\SalesOrderItemValidatorRuleInterface $salesOrderItemValidatorRule
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers
     *
     * @return bool
     */
    protected function isValidationTerminated(
        SalesOrderItemValidatorRuleInterface $salesOrderItemValidatorRule,
        ArrayObject $initialErrorTransfers,
        ArrayObject $postValidationErrorTransfers
    ): bool {
        if (!$salesOrderItemValidatorRule instanceof TerminationAwareValidatorRuleInterface) {
            return false;
        }

        return $postValidationErrorTransfers->count() > $initialErrorTransfers->count();
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    protected function mergeErrors(
        SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $salesOrderItemCollectionResponseErrorTransfers */
        $salesOrderItemCollectionResponseErrorTransfers = $salesOrderItemCollectionResponseTransfer->getErrors();

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorCollectionErrorTransfers */
        $errorCollectionErrorTransfers = $errorCollectionTransfer->getErrors();

        $mergedErrorTransfers = array_merge(
            $salesOrderItemCollectionResponseErrorTransfers->getArrayCopy(),
            $errorCollectionErrorTransfers->getArrayCopy(),
        );

        return $salesOrderItemCollectionResponseTransfer->setErrors(
            new ArrayObject($mergedErrorTransfers),
        );
    }
}
