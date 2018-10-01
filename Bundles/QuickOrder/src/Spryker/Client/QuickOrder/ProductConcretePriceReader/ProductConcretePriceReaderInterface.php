<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\ProductConcretePriceReader;

use Generated\Shared\Transfer\CurrentProductConcretePriceTransfer;

interface ProductConcretePriceReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CurrentProductConcretePriceTransfer $currentProductConcretePriceTransfer
     *
     * @return \Generated\Shared\Transfer\CurrentProductConcretePriceTransfer
     */
    public function getProductConcreteSumPrice(CurrentProductConcretePriceTransfer $currentProductConcretePriceTransfer): CurrentProductConcretePriceTransfer;
}
