<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderItemProductClassExpanderInterface
{
    public function expandOrderItemsWithProductClasses(OrderTransfer $orderTransfer): OrderTransfer;
}
