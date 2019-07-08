<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\Translation\Hydrator;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;

interface SalesOrderThresholdTranslationHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function hydrateLocalizedMessages(SalesOrderThresholdTransfer $salesOrderThresholdTransfer): SalesOrderThresholdTransfer;
}
