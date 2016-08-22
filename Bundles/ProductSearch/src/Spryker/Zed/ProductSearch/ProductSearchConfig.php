<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductSearchConfig extends AbstractBundleConfig
{

    /**
     * @return array
     */
    public function getFilterTypeChoices()
    {
        return [
            'single-select' => 'single-select',
            'multi-select' => 'multi-select',
            'range' => 'range',
            'boolean' => 'boolean',
        ];
    }

}
