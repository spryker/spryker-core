<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductAlternativeTransfer;

interface ProductAlternativeStorageToProductAlternativeFacadeInterface
{
    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function findProductAlternativeTransfer($idProduct): ProductAlternativeTransfer;
}
