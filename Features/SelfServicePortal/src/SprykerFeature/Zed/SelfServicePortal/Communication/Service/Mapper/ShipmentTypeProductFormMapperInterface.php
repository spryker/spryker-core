<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Mapper;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ShipmentTypeProductFormMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array<string, mixed> $formData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapShipmentTypeFormDataToProductConcrete(
        ProductConcreteTransfer $productConcreteTransfer,
        array $formData
    ): ProductConcreteTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array<string, mixed> $formData
     *
     * @return array<string, mixed>
     */
    public function mapProductConcreteShipmentTypeToFormData(
        ProductConcreteTransfer $productConcreteTransfer,
        array $formData
    ): array;
}
