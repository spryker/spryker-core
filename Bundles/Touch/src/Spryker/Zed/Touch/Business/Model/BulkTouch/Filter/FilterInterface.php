<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business\Model\BulkTouch\Filter;

interface FilterInterface
{
    /**
     * @param array $ids
     * @param string $itemType
     *
     * @return array
     */
    public function filter(array $ids, string $itemType): array;
}
