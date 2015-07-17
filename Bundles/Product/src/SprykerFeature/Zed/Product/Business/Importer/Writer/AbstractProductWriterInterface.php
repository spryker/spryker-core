<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Importer\Writer;

use Generated\Shared\Transfer\AbstractProductTransfer;

/**
 * Interface AbstractProductWriterInterface
 */
interface AbstractProductWriterInterface
{

    /**
     * @param AbstractProductTransfer $product
     *
     * @return bool
     */
    public function writeAbstractProduct(AbstractProductTransfer $product);

}
