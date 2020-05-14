<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;

interface MerchantProfileMerchantPortalGuiToMerchantFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function updateMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findOne(MerchantCriteriaTransfer $merchantCriteriaTransfer): ?MerchantTransfer;
}
