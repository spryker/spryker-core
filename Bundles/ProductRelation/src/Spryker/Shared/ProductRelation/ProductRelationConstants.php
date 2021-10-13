<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductRelation;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ProductRelationConstants
{
    /**
     * @var string
     */
    public const RESOURCE_TYPE_PRODUCT_RELATION = 'product_relation';

    /**
     * Specification:
     * - Defines the number of products in the chunk to read.
     *
     * @api
     * @var string
     */
    public const PRODUCT_RELATION_READ_CHUNK = 'PRODUCT_RELATION:PRODUCT_RELATION_READ_CHUNK';

    /**
     * Specification:
     * - Defines the number of products in the chunk to update.
     *
     * @api
     * @var string
     */
    public const PRODUCT_RELATION_UPDATE_CHUNK = 'PRODUCT_RELATION:PRODUCT_RELATION_UPDATE_CHUNK';
}
