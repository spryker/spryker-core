<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesReturnSearch;

use Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\SalesReturnSearch\SalesReturnSearchFactory getFactory()
 */
class SalesReturnSearchClient extends AbstractClient implements SalesReturnSearchClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer $returnReasonSearchRequestTransfer
     *
     * @return array
     */
    public function searchReturnReasons(ReturnReasonSearchRequestTransfer $returnReasonSearchRequestTransfer): array
    {
        return $this->getFactory()
            ->createReturnReasonSearchReader()
            ->searchReturnReasons($returnReasonSearchRequestTransfer);
    }
}
