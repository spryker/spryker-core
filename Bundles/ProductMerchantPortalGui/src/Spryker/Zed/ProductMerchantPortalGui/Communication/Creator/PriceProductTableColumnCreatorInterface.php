<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Creator;

use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductTableColumnCreatorInterface
{
    /**
     * @param string $priceTypeName
     * @param string $moneyValueType
     *
     * @return string
     */
    public function createPriceColumnId(string $priceTypeName, string $moneyValueType): string;

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param array $propertyPathValues
     *
     * @return string
     */
    public function createColumnIdFromPropertyPath(
        PriceProductTransfer $priceProductTransfer,
        array $propertyPathValues
    ): string;

    /**
     * @return string
     */
    public function createVolumeQuantityColumnId(): string;
}
