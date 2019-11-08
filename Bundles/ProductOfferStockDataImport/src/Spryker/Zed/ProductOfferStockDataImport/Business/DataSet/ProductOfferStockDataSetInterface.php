<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStockDataImport\Business\DataSet;

interface ProductOfferStockDataSetInterface
{
    public const QUANTITY = 'quantity';
    public const FK_PRODUCT_OFFER = 'fk_product_offer';
    public const FK_STOCK = 'fk_stock';
    public const PRODUCT_OFFER_REFERENCE = 'product_offer_reference';
    public const PRODUCT_STOCK_NAME = 'stock_name';
}
