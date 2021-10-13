<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AlternativeProductsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class AlternativeProductsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const CONTROLLER_ABSTRACT_ALTERNATIVE_PRODUCTS = 'abstract-alternative-products';
    /**
     * @var string
     */
    public const CONTROLLER_CONCRETE_ALTERNATIVE_PRODUCTS = 'concrete-alternative-products';

    /**
     * @var string
     */
    public const RELATIONSHIP_NAME_ABSTRACT_ALTERNATIVE_PRODUCTS = 'abstract-alternative-products';
    /**
     * @var string
     */
    public const RELATIONSHIP_NAME_CONCRETE_ALTERNATIVE_PRODUCTS = 'concrete-alternative-products';

    /**
     * @var string
     */
    public const ACTION_ABSTRACT_ALTERNATIVE_PRODUCTS_GET = 'get';
    /**
     * @var string
     */
    public const ACTION_CONCRETE_ALTERNATIVE_PRODUCTS_GET = 'get';
}
