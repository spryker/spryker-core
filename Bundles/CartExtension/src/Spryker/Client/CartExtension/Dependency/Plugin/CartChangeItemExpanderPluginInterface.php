<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ItemTransfer;

interface CartChangeItemExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands ItemTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItemTransfer(ItemTransfer $quickOrderItemTransfer): ItemTransfer;
}
