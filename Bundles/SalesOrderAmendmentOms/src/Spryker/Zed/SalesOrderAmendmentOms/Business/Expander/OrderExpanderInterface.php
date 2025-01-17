<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Expander;

interface OrderExpanderInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return list<\Generated\Shared\Transfer\OrderTransfer>
     */
    public function expandOrdersWithIsAmendable(array $orderTransfers): array;
}
