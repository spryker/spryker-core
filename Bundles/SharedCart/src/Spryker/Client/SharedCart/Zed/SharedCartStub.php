<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Zed;

use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;

class SharedCartStub extends ZedRequestStub implements SharedCartStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupResponseTransfer
     */
    public function getQuotePermissionGroupList(QuotePermissionGroupCriteriaFilterTransfer $criteriaFilterTransfer): QuotePermissionGroupResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuotePermissionGroupResponseTransfer $criteriaFilterTransfer */
        $criteriaFilterTransfer = $this->zedStub->call('/shared-cart/gateway/get-quote-permission-groups', $criteriaFilterTransfer);

        return $criteriaFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getShareDetailsByIdQuoteAction(QuoteTransfer $quoteTransfer): ShareDetailCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\ShareDetailCollectionTransfer $shareDetailCollectionTransfer */
        $shareDetailCollectionTransfer = $this->zedStub->call('/shared-cart/gateway/get-share-details-by-id-quote', $quoteTransfer);

        return $shareDetailCollectionTransfer;
    }
}
