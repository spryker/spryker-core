<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui\Communication\Expander;

interface ArrayExpanderInterface
{
    /**
     * @param array<string, mixed> $initialArray
     * @param string $keyAfter
     * @param array<mixed> $itemToInsert
     *
     * @return array<string, mixed>
     */
    public function insertArrayItemAfterKey(array $initialArray, string $keyAfter, array $itemToInsert): array;
}
