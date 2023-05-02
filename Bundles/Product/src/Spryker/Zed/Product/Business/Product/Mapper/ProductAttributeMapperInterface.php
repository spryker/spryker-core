<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Mapper;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes;

interface ProductAttributeMapperInterface
{
    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes $productAbstractLocalizedAttributesEntity
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    public function mapProductAttributeEntityToProductAbstractTransfer(
        SpyProductAbstractLocalizedAttributes $productAbstractLocalizedAttributesEntity,
        LocalizedAttributesTransfer $localizedAttributesTransfer
    ): LocalizedAttributesTransfer;
}
