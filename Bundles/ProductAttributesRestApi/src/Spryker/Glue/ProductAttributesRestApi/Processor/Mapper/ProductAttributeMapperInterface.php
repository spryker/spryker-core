<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\RestProductManagementAttributeAttributesTransfer;

interface ProductAttributeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     * @param \Generated\Shared\Transfer\RestProductManagementAttributeAttributesTransfer $restProductManagementAttributeAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductManagementAttributeAttributesTransfer
     */
    public function mapProductManagementAttributeToRestProductManagementAttributes(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer,
        RestProductManagementAttributeAttributesTransfer $restProductManagementAttributeAttributesTransfer
    ): RestProductManagementAttributeAttributesTransfer;
}
