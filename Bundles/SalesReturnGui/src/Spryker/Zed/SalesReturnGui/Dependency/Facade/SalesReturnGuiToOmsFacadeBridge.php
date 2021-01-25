<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Dependency\Facade;

use Generated\Shared\Transfer\OrderItemFilterTransfer;

class SalesReturnGuiToOmsFacadeBridge implements SalesReturnGuiToOmsFacadeInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\Oms\Business\OmsFacadeInterface $omsFacade
     */
    public function __construct($omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return string[][]
     */
    public function getOrderItemManualEvents(OrderItemFilterTransfer $orderItemFilterTransfer): array
    {
        return $this->omsFacade->getOrderItemManualEvents($orderItemFilterTransfer);
    }
}
