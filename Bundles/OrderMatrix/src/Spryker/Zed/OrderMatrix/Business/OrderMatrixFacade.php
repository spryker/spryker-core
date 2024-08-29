<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderMatrix\Business;

use Generated\Shared\Transfer\IndexedOrderMatrixResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\OrderMatrix\Business\OrderMatrixBusinessFactory getFactory()
 */
class OrderMatrixFacade extends AbstractFacade implements OrderMatrixFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function writeOrderMatrix(): void
    {
        $this->getFactory()->createOrderMatrixWriter()->writeOrderMatrix();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\IndexedOrderMatrixResponseTransfer
     */
    public function getOrderMatrixStatistics(): IndexedOrderMatrixResponseTransfer
    {
        return $this->getFactory()->createOrderMatrixStatisticsReader()->getOrderMatrixStatistics();
    }
}
