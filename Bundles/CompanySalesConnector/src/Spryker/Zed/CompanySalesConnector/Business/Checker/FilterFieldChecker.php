<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Business\Checker;

use Spryker\Zed\CompanySalesConnector\Business\Expander\OrderSearchQueryExpander;

class FilterFieldChecker implements FilterFieldCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     *
     * @return bool
     */
    public function isCompanyFilterApplicable(array $filterFieldTransfers): bool
    {
        foreach ($filterFieldTransfers as $filterFieldTransfer) {
            if ($filterFieldTransfer->getType() === OrderSearchQueryExpander::FILTER_FIELD_TYPE_COMPANY) {
                return true;
            }
        }

        return false;
    }
}
