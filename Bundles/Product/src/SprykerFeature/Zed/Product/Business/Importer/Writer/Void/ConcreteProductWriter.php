<?php

namespace SprykerFeature\Zed\Product\Business\Importer\Writer\Void;

use Generated\Shared\Transfer\ConcreteProductTransfer;
use SprykerFeature\Zed\Product\Business\Importer\Writer\ConcreteProductWriterInterface;

/**
 * Class ConcreteProductWriter
 *
 * @package Zed\Product\Component\Importer\Writer\Null
 */
class ConcreteProductWriter implements ConcreteProductWriterInterface
{
    /**
     * @param ConcreteProductTransfer $product
     *
     * @return bool success
     */
    public function writeProduct(ConcreteProductTransfer $product)
    {
        return is_object($product);
    }
}
