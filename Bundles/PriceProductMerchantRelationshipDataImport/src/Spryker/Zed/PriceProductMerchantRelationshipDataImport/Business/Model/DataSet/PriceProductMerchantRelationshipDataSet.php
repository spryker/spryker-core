<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\DataSet;

interface PriceProductMerchantRelationshipDataSet
{
    public const MERCHANT_RELATIONSHIP_KEY = 'merchant_relation_key';
    public const ABSTRACT_SKU = 'abstract_sku';
    public const CONCRETE_SKU = 'concrete_sku';
    public const PRICE_TYPE = 'price_type';
    public const STORE = 'store';
    public const CURRENCY = 'currency';
    public const PRICE_NET = 'price_net';
    public const PRICE_GROSS = 'price_gross';

    public const ID_MERCHANT_RELATIONSHIP = 'idMerchantRelationship';
    public const ID_PRODUCT_ABSTRACT = 'idProductAbstract';
    public const ID_PRODUCT_CONCRETE = 'idProduct';
    public const ID_CURRENCY = 'idCurrency';
    public const ID_STORE = 'idStore';
}
