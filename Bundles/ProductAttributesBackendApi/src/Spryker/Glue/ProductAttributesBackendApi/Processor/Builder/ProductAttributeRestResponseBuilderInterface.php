<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi\Processor\Builder;

use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;

interface ProductAttributeRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer $productManagementAttributeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createProductAttributesCollectionRestResponse(
        ProductManagementAttributeCollectionTransfer $productManagementAttributeCollectionTransfer
    ): GlueResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createProductAttributesRestResponse(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ): GlueResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createProductAttributeKeyExistsErrorRestResponse(): GlueResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createProductAttributeKeyIsNotProvidedErrorRestResponse(): GlueResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createProductAttributeNotFoundErrorRestResponse(): GlueResponseTransfer;
}
