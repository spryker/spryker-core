<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\ProductAbstractResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;

interface ProductMerchantPortalGuiToMerchantProductFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstract(
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): ?ProductAbstractTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractResponseTransfer
     */
    public function updateProductAbstract(MerchantProductTransfer $merchantProductTransfer): ProductAbstractResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductConcreteCollection(
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): ProductConcreteCollectionTransfer;
}
