<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column;

interface ColumnIdCreatorInterface
{
    /**
     * @return string
     */
    public function createStoreColumnId(): string;

    /**
     * @return string
     */
    public function createCurrencyColumnId(): string;

    /**
     * @param string $priceTypeName
     *
     * @return string
     */
    public function createGrossAmountColumnId(string $priceTypeName): string;

    /**
     * @param string $priceTypeName
     *
     * @return string
     */
    public function createNetAmountColumnId(string $priceTypeName): string;

    /**
     * @return string
     */
    public function createVolumeQuantityColumnId(): string;

    /**
     * @param string $priceTypeName
     * @param string $moneyValueType
     *
     * @return string
     */
    public function createPriceKey(string $priceTypeName, string $moneyValueType): string;
}
