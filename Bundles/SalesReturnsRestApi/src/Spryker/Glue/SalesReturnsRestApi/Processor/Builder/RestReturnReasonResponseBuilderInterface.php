<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Builder;

use Generated\Shared\Transfer\ReturnReasonPageSearchCollectionTransfer;
use Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface RestReturnReasonResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer $returnReasonSearchRequestTransfer
     * @param \Generated\Shared\Transfer\ReturnReasonPageSearchCollectionTransfer $returnReasonPageSearchCollectionTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createReturnReasonListRestResponse(
        ReturnReasonSearchRequestTransfer $returnReasonSearchRequestTransfer,
        ReturnReasonPageSearchCollectionTransfer $returnReasonPageSearchCollectionTransfer,
        string $localeName
    ): RestResponseInterface;
}
