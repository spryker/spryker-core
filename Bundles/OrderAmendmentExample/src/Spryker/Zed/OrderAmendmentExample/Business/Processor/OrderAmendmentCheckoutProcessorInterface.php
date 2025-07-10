<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderAmendmentExample\Business\Processor;

interface OrderAmendmentCheckoutProcessorInterface
{
    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param string $orderReference
     * @param int $idSalesOrder
     *
     * @return array<mixed>
     */
    public function processOrderAmendmentCheckout(array $orderItems, string $orderReference, int $idSalesOrder): array;
}
