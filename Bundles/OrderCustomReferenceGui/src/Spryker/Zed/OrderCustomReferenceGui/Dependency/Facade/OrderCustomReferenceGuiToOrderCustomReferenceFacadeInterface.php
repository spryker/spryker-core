<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReferenceGui\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderCustomReferenceGuiToOrderCustomReferenceFacadeInterface
{
    /**
     * @param string $orderCustomReference
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function updateOrderCustomReference(string $orderCustomReference, OrderTransfer $orderTransfer): void;
}
