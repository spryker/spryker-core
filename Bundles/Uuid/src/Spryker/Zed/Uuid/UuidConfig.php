<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Uuid;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class UuidConfig extends AbstractBundleConfig
{
    protected const UUID_GENERATOR_BATCH_SIZE = 200;

    /**
     * Specification:
     * - Returns the batch size for the uuid generation operation.
     *
     * @return int
     */
    public function getUuidGeneratorBatchSize(): int
    {
        return static::UUID_GENERATOR_BATCH_SIZE;
    }
}
