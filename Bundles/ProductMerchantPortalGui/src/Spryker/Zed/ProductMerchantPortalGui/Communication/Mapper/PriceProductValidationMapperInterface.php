<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ValidationResponseTransfer;

interface PriceProductValidationMapperInterface
{
    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param array<mixed> $initialData
     *
     * @return array<mixed>
     */
    public function mapValidationResponseTransferToInitialData(
        ValidationResponseTransfer $validationResponseTransfer,
        ArrayObject $priceProductTransfers,
        array $initialData
    ): array;
}
