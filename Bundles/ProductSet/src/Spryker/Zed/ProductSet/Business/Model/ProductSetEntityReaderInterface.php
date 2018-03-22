<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model;

use Generated\Shared\Transfer\ProductSetTransfer;

interface ProductSetEntityReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @throws \Spryker\Zed\ProductSet\Business\Exception\ProductSetNotFoundException
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSet
     */
    public function getProductSetEntity(ProductSetTransfer $productSetTransfer);
}
