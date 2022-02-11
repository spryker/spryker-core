<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui\Communication\Expander;

class ArrayExpander implements ArrayExpanderInterface
{
    /**
     * @param array<string, mixed> $initialArray
     * @param string $keyAfter
     * @param array<mixed> $itemToInsert
     *
     * @return array<string, mixed>
     */
    public function insertArrayItemAfterKey(array $initialArray, string $keyAfter, array $itemToInsert): array
    {
        $keys = array_keys($initialArray);
        $index = array_search($keyAfter, $keys);
        $pos = $index === false ? count($initialArray) : $index + 1;

        return array_merge(array_slice($initialArray, 0, $pos), $itemToInsert, array_slice($initialArray, $pos));
    }
}
