<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Dependency;

interface MerchantProductEvents
{
    /**
     * Specification
     * - This event will be used for merchant product abstract publishing.
     *
     * @api
     */
    public const MERCHANT_PRODUCT_ABSTRACT_PUBLISH = 'MerchantProductAbstract.publish';

    /**
     * Specification
     * - This event will be used for merchant product abstract un-publishing.
     *
     * @api
     */
    public const MERCHANT_PRODUCT_ABSTRACT_UNPUBLISH = 'MerchantProductAbstract.unpublish';
}
