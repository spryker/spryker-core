<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityStorage\Mapper;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;

interface AvailabilityStorageMapperInterface
{
    /**
     * @param array $availabilityStorageData
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function mapAvailabilityStorageDataToProductAbstractAvailabilityTransfer(
        array $availabilityStorageData,
        ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
    ): ProductAbstractAvailabilityTransfer;
}
