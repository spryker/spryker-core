<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Mapper;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductAbstractTypeProductFormMapperInterface
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
    ): ProductAbstractTransfer;
}
