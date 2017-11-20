<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Touch;

use Generated\Shared\Transfer\ProductSetTransfer;

interface ProductSetTouchInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return bool
     */
    public function touchProductSetActive(ProductSetTransfer $productSetTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return bool
     */
    public function touchProductSetDeleted(ProductSetTransfer $productSetTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return bool
     */
    public function touchProductSetByStatus(ProductSetTransfer $productSetTransfer);
}
