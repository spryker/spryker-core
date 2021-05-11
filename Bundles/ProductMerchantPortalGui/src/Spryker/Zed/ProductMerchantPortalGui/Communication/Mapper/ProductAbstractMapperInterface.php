<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductAbstractMapperInterface
{
    /**
     * @param mixed[] $formData
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapFormDataToProductAbstractTransfer(
        array $formData,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer;
}
