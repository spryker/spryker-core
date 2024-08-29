<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderMatrix\Dependency\Facade;

use Generated\Shared\Transfer\OrderMatrixCollectionTransfer;
use Generated\Shared\Transfer\OrderMatrixCriteriaTransfer;

/**
 * @method \Spryker\Zed\OrderMatrix\Business\OrderMatrixBusinessFactory getFactory()
 */
class OrderMatrixToOmsFacadeBridge implements OrderMatrixToOmsFacadeInterface
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
     * @param \Generated\Shared\Transfer\OrderMatrixCriteriaTransfer $orderMatrixCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OrderMatrixCollectionTransfer
     */
    public function getOrderMatrixCollection(OrderMatrixCriteriaTransfer $orderMatrixCriteriaTransfer): OrderMatrixCollectionTransfer
    {
        return $this->omsFacade->getOrderMatrixCollection($orderMatrixCriteriaTransfer);
    }

    /**
     * @return array<int|string, string|null>
     */
    public function getProcessNamesIndexedByIdOmsOrderProcess(): array
    {
        return $this->omsFacade->getProcessNamesIndexedByIdOmsOrderProcess();
    }
}
