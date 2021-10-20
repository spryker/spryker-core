<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductAvailabilitiesRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_ABSTRACT_PRODUCT_AVAILABILITIES = 'abstract-product-availabilities';

    /**
     * @var string
     */
    public const RESOURCE_CONCRETE_PRODUCT_AVAILABILITIES = 'concrete-product-availabilities';

    /**
     * @var string
     */
    public const RESPONSE_CODE_ABSTRACT_PRODUCT_AVAILABILITY_NOT_FOUND = '305';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_ABSTRACT_PRODUCT_AVAILABILITY_NOT_FOUND = 'Availability is not found.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CONCRETE_PRODUCT_AVAILABILITY_NOT_FOUND = '306';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_CONCRETE_PRODUCT_AVAILABILITY_NOT_FOUND = 'Availability is not found.';
}
