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

    const PRODUCT_TYPE_BUNDLE = 'bundle';
    const PRODUCT_TYPE_REGULAR = 'regular';

    /**
     * @return array
     */
    public function getAttributeTypeChoices()
    {
        return [
            'text' => 'text',
            'textarea' => 'textarea',
            'number' => 'number',
            'float' => 'float',
            'date' => 'date',
            'time' => 'time',
            'datetime' => 'datetime',
            'select' => 'select',
        ];
    }

    /**
     * @return string
     */
    public function getImageUrlPrefix()
    {
        return $this->get(ProductManagementConstants::PRODUCT_MANAGEMENT_URL_PREFIX);
    }

    /**
     * @return string
     */
    public function getHostYves()
    {
        return $this->get(ProductManagementConstants::PRODUCT_MANAGEMENT_URL_PREFIX);
    }

}
