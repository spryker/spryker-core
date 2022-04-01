<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\ElasticBatch;

interface ElasticBatchInterface
{
    /**
     * Specification:
     * - Checks if current batch is full.
     *
     * @return bool
     */
    public function isFull(): bool;

    /**
     * Specification:
     * - Resets batch state.
     *
     * @return void
     */
    public function reset(): void;
}
