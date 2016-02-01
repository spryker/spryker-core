<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
