<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderMatrixGui\Dependency\Facade;

use Generated\Shared\Transfer\IndexedOrderMatrixResponseTransfer;

class OrderMatrixGuiToOrderMatrixFacadeBridge implements OrderMatrixGuiToOrderMatrixFacadeInterface
{
    /**
     * @var \Spryker\Zed\OrderMatrix\Business\OrderMatrixFacadeInterface
     */
    protected $orderMatrixFacade;

    /**
     * @param \Spryker\Zed\OrderMatrix\Business\OrderMatrixFacadeInterface $orderMatrixFacade
     */
    public function __construct($orderMatrixFacade)
    {
        $this->orderMatrixFacade = $orderMatrixFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\IndexedOrderMatrixResponseTransfer
     */
    public function getOrderMatrixStatistics(): IndexedOrderMatrixResponseTransfer
    {
        return $this->orderMatrixFacade->getOrderMatrixStatistics();
    }
}
