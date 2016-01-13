<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Importer\Writer;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductConcreteWriterInterface
{

    /**
     * @param ProductConcreteTransfer $productConcreteTransfer
     *
     * @return bool success
     */
    public function writeProduct(ProductConcreteTransfer $productConcreteTransfer);

}
