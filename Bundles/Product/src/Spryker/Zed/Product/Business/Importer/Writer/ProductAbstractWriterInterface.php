<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Importer\Writer;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductAbstractWriterInterface
{

    /**
     * @param ProductAbstractTransfer $product
     *
     * @return bool
     */
    public function writeProductAbstract(ProductAbstractTransfer $product);

}
