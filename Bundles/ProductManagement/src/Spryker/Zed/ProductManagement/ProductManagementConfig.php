<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement;

use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductManagementConfig extends AbstractBundleConfig
{
    public const PRODUCT_TYPE_BUNDLE = 'bundle';
    public const PRODUCT_TYPE_REGULAR = 'regular';

    /**
     * Used for validity datetimes transformation and displaying in messages.
     * Hydration validity format is described in ProductValidity module.
     * @see \Spryker\Zed\ProductValidity\Business\Validity\ProductValidityHydrator::VALIDITY_DATE_TIME_FORMAT
     */
    public const VALIDITY_DATE_TIME_FORMAT = 'Y-m-d G:i';

    /**
     * @return string
     */
    public function getImageUrlPrefix()
    {
        return $this->get(ProductManagementConstants::BASE_URL_YVES);
    }

    /**
     * @return string
     */
    public function getHostYves()
    {
        return $this->get(ProductManagementConstants::BASE_URL_YVES);
    }

    /**
     * @return string
     */
    public function getValidityTimeFormat()
    {
        return static::VALIDITY_DATE_TIME_FORMAT;
    }
}
