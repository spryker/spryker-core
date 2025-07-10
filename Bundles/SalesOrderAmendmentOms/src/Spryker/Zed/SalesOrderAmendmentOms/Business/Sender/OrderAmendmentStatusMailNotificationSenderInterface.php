<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Sender;

interface OrderAmendmentStatusMailNotificationSenderInterface
{
    /**
     * @param string $orderReference
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function notifyOrderAmendmentApplied(string $orderReference, int $idSalesOrder): void;

    /**
     * @param string $orderReference
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function notifyOrderAmendmentFailed(string $orderReference, int $idSalesOrder): void;
}
