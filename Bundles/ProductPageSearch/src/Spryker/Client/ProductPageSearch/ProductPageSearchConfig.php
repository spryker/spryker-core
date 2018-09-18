<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPageSearch;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConstants;

class ProductPageSearchConfig extends AbstractBundleConfig
{
    /**
     * @return int
     */
    public function getFullTextBoostedBoostingValue()
    {
        return $this->get(ProductPageSearchConstants::FULL_TEXT_BOOSTED_BOOSTING_VALUE);
    }
}
