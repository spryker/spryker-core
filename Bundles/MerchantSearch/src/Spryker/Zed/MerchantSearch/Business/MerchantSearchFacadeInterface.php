<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Business;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;

interface MerchantSearchFacadeInterface
{
    /**
     * Specification:
     * - Returns the list of active merchants.
     * - Can be filtered by MerchantCriteriaFilterTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getActiveMerchants(MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer): MerchantCollectionTransfer;
}
