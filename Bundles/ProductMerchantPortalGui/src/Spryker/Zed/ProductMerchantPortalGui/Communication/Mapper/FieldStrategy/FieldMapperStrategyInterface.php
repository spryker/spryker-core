<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FieldStrategy;

use ArrayObject;

interface FieldMapperStrategyInterface
{
    /**
     * @param string $dataField
     *
     * @return bool
     */
    public function isApplicable(string $dataField): bool;

    /**
     * @param array<string, mixed> $data
     * @param int $volumeQuantity
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function mapDataToPriceProductTransfers(
        array $data,
        int $volumeQuantity,
        ArrayObject $priceProductTransfers
    ): ArrayObject;
}
