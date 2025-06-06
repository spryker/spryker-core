<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDiscountConnector\Dependency\Facade;

use Generated\Shared\Transfer\ClauseTransfer;

interface CustomerDiscountConnectorToDiscountFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param mixed $compareWith
     *
     * @return bool
     */
    public function queryStringCompare(ClauseTransfer $clauseTransfer, mixed $compareWith): bool;
}
