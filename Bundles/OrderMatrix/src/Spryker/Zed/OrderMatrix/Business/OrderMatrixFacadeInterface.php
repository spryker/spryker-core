<?php

 /**
  * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
  * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
  */

namespace Spryker\Zed\OrderMatrix\Business;

use Generated\Shared\Transfer\IndexedOrderMatrixResponseTransfer;

/**
 * @method \Spryker\Zed\OrderMatrix\Business\OrderMatrixBusinessFactory getFactory()
 */
interface OrderMatrixFacadeInterface
{
    /**
     * Specification:
     * - Writes the order matrix to the storage.
     *
     * @api
     *
     * @return void
     */
    public function writeOrderMatrix(): void;

    /**
     * Specification:
     * - Returns the order matrix statistics.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\IndexedOrderMatrixResponseTransfer
     */
    public function getOrderMatrixStatistics(): IndexedOrderMatrixResponseTransfer;
}
