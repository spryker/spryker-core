<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\RestProductAttributesBackendAttributesTransfer;

interface ProductAttributeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     * @param \Generated\Shared\Transfer\RestProductAttributesBackendAttributesTransfer $restProductAttributesBackendAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductAttributesBackendAttributesTransfer
     */
    public function mapProductManagementAttributeTransferToRestProductAttributesBackendAttributesTransfer(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer,
        RestProductAttributesBackendAttributesTransfer $restProductAttributesBackendAttributesTransfer
    ): RestProductAttributesBackendAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\RestProductAttributesBackendAttributesTransfer $restProductAttributesBackendAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function mapRestProductAttributesBackendAttributesTransferToProductManagementAttributeTransfer(
        RestProductAttributesBackendAttributesTransfer $restProductAttributesBackendAttributesTransfer,
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ): ProductManagementAttributeTransfer;
}
