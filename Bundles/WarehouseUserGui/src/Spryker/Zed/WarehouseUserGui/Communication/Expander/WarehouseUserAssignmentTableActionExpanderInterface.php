<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Communication\Expander;

interface WarehouseUserAssignmentTableActionExpanderInterface
{
    /**
     * @param array<string, mixed> $user
     *
     * @return list<\Generated\Shared\Transfer\ButtonTransfer>
     */
    public function expand(array $user): array;
}
