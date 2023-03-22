<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Builder;

use Generated\Shared\Transfer\ItemTransfer;

interface ItemIdentifierBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @throws \Spryker\Zed\PriceCartConnector\Business\Exception\TransferPropertyNotFoundException
     *
     * @return string
     */
    public function buildItemIdentifier(ItemTransfer $itemTransfer): string;
}
