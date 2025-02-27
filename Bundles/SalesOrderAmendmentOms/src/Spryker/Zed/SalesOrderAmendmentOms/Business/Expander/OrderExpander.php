<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Expander;

use Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\OrderValidatorInterface;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\OrderValidatorInterface $orderValidator
     */
    public function __construct(protected OrderValidatorInterface $orderValidator)
    {
    }

    /**
     * @param list<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return list<\Generated\Shared\Transfer\OrderTransfer>
     */
    public function expandOrdersWithIsAmendable(array $orderTransfers): array
    {
        foreach ($orderTransfers as $orderTransfer) {
            if ($orderTransfer->getIsAmendable() !== null) {
                continue;
            }

            $orderTransfer->setIsAmendable(
                $this->orderValidator->validateIsOrderAmendable($orderTransfer->getOrderReferenceOrFail()),
            );
        }

        return $orderTransfers;
    }
}
