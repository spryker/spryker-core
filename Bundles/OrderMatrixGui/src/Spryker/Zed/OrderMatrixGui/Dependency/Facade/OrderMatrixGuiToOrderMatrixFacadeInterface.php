<?php

 /**
  * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
  * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
  */

namespace Spryker\Zed\OrderMatrixGui\Dependency\Facade;

use Generated\Shared\Transfer\IndexedOrderMatrixResponseTransfer;

interface OrderMatrixGuiToOrderMatrixFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\IndexedOrderMatrixResponseTransfer
     */
    public function getOrderMatrixStatistics(): IndexedOrderMatrixResponseTransfer;
}
