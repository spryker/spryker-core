<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Validator;

use Generated\Shared\Transfer\OrderTransfer;

interface PickingListStatusValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPickingListGenerationFinishedForOrder(OrderTransfer $orderTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPickingStartedForOrder(OrderTransfer $orderTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPickingFinishedForOrder(OrderTransfer $orderTransfer): bool;
}
