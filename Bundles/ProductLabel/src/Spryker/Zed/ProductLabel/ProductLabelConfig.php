<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel;

use Spryker\Shared\ProductLabel\ProductLabelConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductLabelConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const PRODUCT_LABEL_TO_DE_ASSIGN_CHUNK_SIZE = 1000;

    /**
     * Specification:
     * - Returns the number of product label relations in the chunk to be deassigned.
     *
     * @api
     *
     * @return int
     */
    public function getProductLabelToDeAssignChunkSize(): int
    {
        return $this->get(
            ProductLabelConstants::PRODUCT_LABEL_TO_DE_ASSIGN_CHUNK_SIZE,
            static::PRODUCT_LABEL_TO_DE_ASSIGN_CHUNK_SIZE,
        );
    }
}
