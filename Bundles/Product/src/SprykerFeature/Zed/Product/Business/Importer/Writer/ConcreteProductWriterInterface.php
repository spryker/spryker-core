<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Importer\Writer;

use Generated\Shared\Transfer\ConcreteProductTransfer;

/**
 * Interface ProductWriterInterface
 */
interface ConcreteProductWriterInterface
{

    /**
     * @param ConcreteProductTransfer $product
     *
     * @return bool success
     */
    public function writeProduct(ConcreteProductTransfer $product);

}
