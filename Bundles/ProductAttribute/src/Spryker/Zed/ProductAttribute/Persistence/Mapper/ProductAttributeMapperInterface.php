<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Persistence\Mapper;

use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;

interface ProductAttributeMapperInterface
{
    /**
     * @param \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute $productManagementAttributeEntity
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function mapProductManagementAttributeEntityToTransfer(SpyProductManagementAttribute $productManagementAttributeEntity, ProductManagementAttributeTransfer $productManagementAttributeTransfer);
}
