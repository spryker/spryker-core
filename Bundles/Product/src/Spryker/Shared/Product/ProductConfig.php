<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Product;

interface ProductConfig
{
    /**
     * @var string
     */
    public const RESOURCE_TYPE_PRODUCT_ABSTRACT = 'product_abstract';

    /**
     * @var string
     */
    public const RESOURCE_TYPE_PRODUCT_CONCRETE = 'product_concrete';

    /**
     * @var string
     */
    public const RESOURCE_TYPE_ATTRIBUTE_MAP = 'attribute_map';

    /**
     * @var string
     */
    public const ATTRIBUTE_MAP_PATH_DELIMITER = ':';

    /**
     * @var string
     */
    public const VARIANT_LEAF_NODE_ID = 'id_product_concrete';
}
