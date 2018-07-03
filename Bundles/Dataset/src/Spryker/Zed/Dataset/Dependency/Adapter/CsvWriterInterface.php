<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Dependency\Adapter;

interface CsvWriterInterface
{
    /**
     * @param array $values
     *
     * @return int
     */
    public function insertOne(array $values): int;

    /**
     * @return string
     */
    public function getContent(): string;
}
