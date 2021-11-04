<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_ABSTRACT_PRODUCTS = 'abstract-products';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CANT_FIND_ABSTRACT_PRODUCT = '301';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_CANT_FIND_ABSTRACT_PRODUCT = 'Abstract product is not found.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED = '311';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED = 'Abstract product sku is not specified.';

    /**
     * @var string
     */
    public const RESOURCE_CONCRETE_PRODUCTS = 'concrete-products';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CANT_FIND_CONCRETE_PRODUCT = '302';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_CANT_FIND_CONCRETE_PRODUCT = 'Concrete product is not found.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED = '312';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED = 'Concrete product sku is not specified.';

    /**
     * @var bool
     */
    protected const ALLOW_PRODUCT_CONCRETE_EAGER_RELATIONSHIP = true;

    /**
     * @api
     *
     * @return bool
     */
    public function getAllowedProductConcreteEagerRelationship(): bool
    {
        return static::ALLOW_PRODUCT_CONCRETE_EAGER_RELATIONSHIP;
    }
}
