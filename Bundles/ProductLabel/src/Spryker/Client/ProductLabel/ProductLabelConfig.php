<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabel;

use Spryker\Client\Kernel\AbstractBundleConfig;

class ProductLabelConfig extends AbstractBundleConfig
{

    const MAX_NUMBER_OF_LABELS = 2;

    /**
     * @return int
     */
    public function getMaxNumberOfLabels()
    {
        return static::MAX_NUMBER_OF_LABELS;
    }

}
