<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Mapper;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ProductAbstractTypeForm;

class ProductAbstractTypeProductFormMapper implements ProductAbstractTypeProductFormMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array<string, mixed> $formData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapProductAbstractTypeFormDataToProductAbstract(
        ProductAbstractTransfer $productAbstractTransfer,
        array $formData
    ): ProductAbstractTransfer {
        if (!isset($formData[ProductAbstractTypeForm::FIELD_PRODUCT_ABSTRACT_TYPES])) {
            return $productAbstractTransfer;
        }

        $productAbstractTypes = $formData[ProductAbstractTypeForm::FIELD_PRODUCT_ABSTRACT_TYPES];
        $productAbstractTransfer->setProductAbstractTypes($productAbstractTypes);

        return $productAbstractTransfer;
    }
}
