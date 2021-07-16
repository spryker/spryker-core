<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business\Reader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;

interface ProductAbstractIdsRefreshReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function getProductProductPageLoadTransferForRefresh(): ProductPageLoadTransfer;
}
