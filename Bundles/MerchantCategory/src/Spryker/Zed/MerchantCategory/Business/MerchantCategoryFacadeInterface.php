<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Business;

use Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer;
use Generated\Shared\Transfer\MerchantCategoryTransfer;

interface MerchantCategoryFacadeInterface
{
    /**
     * Specification:
     * - Returns transfer with list of merchant categories by provided criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCategoryTransfer
     */
    public function get(MerchantCategoryCriteriaTransfer $merchantCriteriaTransfer): MerchantCategoryTransfer;
}
