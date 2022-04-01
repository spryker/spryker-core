<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\Translation\Hydrator;

interface SalesOrderThresholdTranslationHydratorInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\SalesOrderThresholdTransfer> $salesOrderThresholdTransfers
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderThresholdTransfer>
     */
    public function expandWithLocalizedMessagesCollection(array $salesOrderThresholdTransfers): array;
}
