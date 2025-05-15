<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\Propel;

use Spryker\Zed\Propel\Persistence\BatchProcessor\CascadeActiveRecordBatchProcessorTrait;

class TestCascadeProcessor
{
    use CascadeActiveRecordBatchProcessorTrait;

    public function __construct()
    {
        static::$entityList = [];
    }

    /**
     * @return array
     */
    public function getEntityList(): array
    {
        return static::$entityList;
    }
}
