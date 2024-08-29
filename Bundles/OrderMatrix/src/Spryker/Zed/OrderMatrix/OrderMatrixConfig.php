<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderMatrix;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class OrderMatrixConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const ORDER_MATRIX_BATCH_SIZE = 100000;

    /**
     * @var string
     */
    protected const ORDER_MATRIX_STORAGE_KEY = 'order_matrix';

    /**
     * Specification:
     * - Returns the batch size for the order matrix.
     *
     * @api
     *
     * @return int
     */
    public function getOrderMatrixBatchSize(): int
    {
        return static::ORDER_MATRIX_BATCH_SIZE;
    }

    /**
     * Specification:
     * - Returns the storage key for the order matrix.
     *
     * @api
     *
     * @return string
     */
    public function getOrderMatrixStorageKey(): string
    {
        return static::ORDER_MATRIX_STORAGE_KEY;
    }
}
