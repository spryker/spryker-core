<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductRelation;

class ProductRelationTypes
{
    public const TYPE_RELATED_PRODUCTS = 'related-products';
    public const TYPE_UP_SELLING = 'up-selling';

    /**
     * @return array
     */
    public static function getAvailableRelationTypes()
    {
        return [
            static::TYPE_RELATED_PRODUCTS,
            static::TYPE_UP_SELLING,
        ];
    }
}
