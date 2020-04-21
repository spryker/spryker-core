<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker;

use Generated\Shared\Transfer\FilterFieldTransfer;

class FilterFieldChecker implements FilterFieldCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     * @param string $type
     *
     * @return bool
     */
    public function isFilterFieldSet(array $filterFieldTransfers, string $type): bool
    {
        return $this->extractFilterFieldByType($filterFieldTransfers, $type) !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\FilterFieldTransfer|null
     */
    protected function extractFilterFieldByType(array $filterFieldTransfers, string $type): ?FilterFieldTransfer
    {
        foreach ($filterFieldTransfers as $filterFieldTransfer) {
            if ($filterFieldTransfer->getType() === $type) {
                return $filterFieldTransfer;
            }
        }

        return null;
    }
}
