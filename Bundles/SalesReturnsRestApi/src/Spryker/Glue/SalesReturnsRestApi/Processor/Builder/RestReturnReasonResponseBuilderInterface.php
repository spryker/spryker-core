<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Builder;

use Generated\Shared\Transfer\ReturnReasonCollectionTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface RestReturnReasonResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnReasonFilterTransfer $returnReasonFilterTransfer
     * @param \Generated\Shared\Transfer\ReturnReasonCollectionTransfer $returnReasonCollectionTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createReturnReasonListRestResponse(
        ReturnReasonFilterTransfer $returnReasonFilterTransfer,
        ReturnReasonCollectionTransfer $returnReasonCollectionTransfer,
        string $localeName
    ): RestResponseInterface;
}
