<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Validator;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface;
use Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig;

class OrderValidator implements OrderValidatorInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig
     */
    protected SalesOrderAmendmentOmsConfig $salesOrderAmendmentOmsConfig;

    /**
     * @var \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface
     */
    protected SalesOrderAmendmentOmsToOmsFacadeInterface $omsFacade;

    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig $salesOrderAmendmentOmsConfig
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface $omsFacade
     */
    public function __construct(
        SalesOrderAmendmentOmsConfig $salesOrderAmendmentOmsConfig,
        SalesOrderAmendmentOmsToOmsFacadeInterface $omsFacade
    ) {
        $this->salesOrderAmendmentOmsConfig = $salesOrderAmendmentOmsConfig;
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param string $orderReference
     *
     * @return bool
     */
    public function validateIsOrderAmendable(string $orderReference): bool
    {
        return $this->areOrderItemsSatisfiedByFlag(
            $orderReference,
            $this->salesOrderAmendmentOmsConfig->getAmendableOmsFlag(),
        );
    }

    /**
     * @param string $orderReference
     *
     * @return bool
     */
    public function validateIsOrderBeingAmended(string $orderReference): bool
    {
        return $this->areOrderItemsSatisfiedByFlag(
            $orderReference,
            $this->salesOrderAmendmentOmsConfig->getAmendmentInProgressOmsFlag(),
        );
    }

    /**
     * @param string $orderReference
     * @param string $flag
     *
     * @return bool
     */
    protected function areOrderItemsSatisfiedByFlag(string $orderReference, string $flag): bool
    {
        $orderTransfer = (new OrderTransfer())->setOrderReference($orderReference);

        return $this->omsFacade->areOrderItemsSatisfiedByFlag($orderTransfer, $flag);
    }
}
