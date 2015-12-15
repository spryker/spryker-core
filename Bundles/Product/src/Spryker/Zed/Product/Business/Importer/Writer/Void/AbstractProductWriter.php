<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Importer\Writer\Void;

use Generated\Shared\Transfer\AbstractProductTransfer;
use Spryker\Zed\Product\Business\Importer\Writer\AbstractProductWriterInterface;

class AbstractProductWriter implements AbstractProductWriterInterface
{

    /**
     * @param AbstractProductTransfer $product
     *
     * @return bool
     */
    public function writeAbstractProduct(AbstractProductTransfer $product)
    {
        return is_object($product);
    }

}
