<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Checker;

interface ConditionCheckerInterface
{
    /**
     * @param string $orderReference
     *
     * @return bool
     */
    public function isOrderAmendmentDraftSuccessfullyApplied(string $orderReference): bool;
}
