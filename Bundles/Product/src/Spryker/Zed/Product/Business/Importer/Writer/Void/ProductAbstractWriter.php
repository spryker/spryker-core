<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Importer\Writer\Void;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Product\Business\Importer\Writer\ProductAbstractWriterInterface;

class ProductAbstractWriter implements ProductAbstractWriterInterface
{

    /**
     * @param ProductAbstractTransfer $product
     *
     * @return bool
     */
    public function writeProductAbstract(ProductAbstractTransfer $product)
    {
        return is_object($product);
    }

}
