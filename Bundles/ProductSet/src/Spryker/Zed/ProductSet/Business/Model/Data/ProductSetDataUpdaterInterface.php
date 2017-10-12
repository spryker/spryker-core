<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Data;

use Generated\Shared\Transfer\LocalizedProductSetTransfer;

interface ProductSetDataUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\LocalizedProductSetTransfer $localizedProductSetTransfer
     * @param int $idProductSet
     *
     * @return \Generated\Shared\Transfer\LocalizedProductSetTransfer
     */
    public function updateProductSetData(LocalizedProductSetTransfer $localizedProductSetTransfer, $idProductSet);
}
