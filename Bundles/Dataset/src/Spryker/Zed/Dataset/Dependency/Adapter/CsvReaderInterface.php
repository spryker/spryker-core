<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Dependency\Adapter;

use Iterator;

interface CsvReaderInterface
{
    /**
     * @param int $offset
     *
     * @return $this
     */
    public function setHeaderOffset(int $offset);

    /**
     * @return array
     */
    public function getHeader(): array;

    /**
     * @return \Iterator
     */
    public function getRecords(): Iterator;
}
