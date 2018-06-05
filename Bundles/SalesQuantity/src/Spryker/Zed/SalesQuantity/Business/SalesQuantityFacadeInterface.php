<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\SalesQuantity\Business;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;

/**
 * @method \Spryker\Zed\SalesQuantity\Business\SalesQuantityBusinessFactory getFactory()
 */
interface SalesQuantityFacadeInterface
{
    /**
     * Specification:
     *  - Avoids to expand order by quantity 1 recalculates order transfer with new values if needed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return null|\ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItem(ItemTransfer $itemTransfer): ?ArrayObject;
}
