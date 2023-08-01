<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Business\Expander;

interface ServicePointExpanderInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithServicePoint(array $itemTransfers): array;
}
