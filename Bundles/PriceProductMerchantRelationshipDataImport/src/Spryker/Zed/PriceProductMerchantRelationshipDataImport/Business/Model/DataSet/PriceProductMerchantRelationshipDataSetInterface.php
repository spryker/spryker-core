<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\DataSet;

interface PriceProductMerchantRelationshipDataSetInterface
{
    public const MERCHANT_RELATIONSHIP_KEY = 'merchant_relation_key';
    public const ABSTRACT_SKU = 'abstract_sku';
    public const CONCRETE_SKU = 'concrete_sku';
    public const PRICE_TYPE = 'price_type';
    public const STORE = 'store';
    public const CURRENCY = 'currency';
    public const PRICE_NET = 'price_net';
    public const PRICE_GROSS = 'price_gross';

    public const ID_CURRENCY = 'id_currency';
    public const ID_PRICE_PRODUCT = 'id_price_product';
    public const ID_MERCHANT_RELATIONSHIP = 'id_merchant_relationship';
    public const ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    public const ID_PRODUCT_CONCRETE = 'id_product';
    public const ID_STORE = 'id_store';
}
