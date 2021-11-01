<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductAttributeConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const DEFAULT_LOCALE = '_';

    /**
     * @api
     *
     * @return array<string>
     */
    public function getAllowedStores()
    {
        return Store::getInstance()->getAllowedStores();
    }

    /**
     * @api
     *
     * @return array<string, string>
     */
    public function getAttributeAvailableTypes()
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
}
