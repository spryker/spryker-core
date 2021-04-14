<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Shared\ProductLabel\ProductLabelConstants;

class ProductLabelConfig extends AbstractBundleConfig
{
    protected const PRODUCT_LABEL_DE_ASSIGN_CHUNK_SIZE = 1000;

    /**
     * @api
     *
     * @return int
     */
    public function getProductLabelDeAssignChankSize(): int
    {
        return $this->get(
            ProductLabelConstants::PRODUCT_LABEL_DE_ASSIGN_CHUNK_SIZE,
            static::PRODUCT_LABEL_DE_ASSIGN_CHUNK_SIZE
        );
    }
}
