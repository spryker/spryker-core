<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesReturn;

use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;

interface SalesReturnClientInterface
{
    /**
     * Specification:
     * - Makes Zed request.
     * - Applies default offset, limit, sort field and direction.
     * - Retrieves returns from Persistence by criteria from ReturnFilterTransfer.
     * - Expands found returns with return item, totals, sales order items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    public function getReturns(ReturnFilterTransfer $returnFilterTransfer): ReturnCollectionTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Validates return request.
     * - Expects that order items have same currency.
     * - Expects order items state to be returnable.
     * - Generates unique reference number.
     * - Stores return.
     * - Stores return items.
     * - Triggers return event for provided order items.
     * - Returns "isSuccessful=true" and return transfer on success or `isSuccessful=false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturn(ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnResponseTransfer;
}
