<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Importer\Writer\Void;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Importer\Writer\ProductConcreteWriterInterface;

class ProductConcreteWriter implements ProductConcreteWriterInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return bool success
     */
    public function writeProduct(ProductConcreteTransfer $productConcreteTransfer)
    {
        return is_object($productConcreteTransfer);
    }

}
