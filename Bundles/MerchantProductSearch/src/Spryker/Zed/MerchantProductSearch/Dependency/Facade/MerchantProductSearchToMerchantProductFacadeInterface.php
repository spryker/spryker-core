<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Dependency\Facade;

use Generated\Shared\Transfer\MerchantProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;

interface MerchantProductSearchToMerchantProductFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchant(MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer): ?MerchantTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantProductAbstractCriteriaTransfer $merchantProductAbstractCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductAbstractCollectionTransfer
     */
    public function getMerchantProductAbstractCollection(
        MerchantProductAbstractCriteriaTransfer $merchantProductAbstractCriteriaTransfer
    ): MerchantProductAbstractCollectionTransfer;
}
