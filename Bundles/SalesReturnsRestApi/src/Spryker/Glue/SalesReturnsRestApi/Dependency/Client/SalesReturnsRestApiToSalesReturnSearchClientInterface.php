<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer;

interface SalesReturnsRestApiToSalesReturnSearchClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer $returnReasonSearchRequestTransfer
     *
     * @return array
     */
    public function searchReturnReasons(ReturnReasonSearchRequestTransfer $returnReasonSearchRequestTransfer): array;
}
