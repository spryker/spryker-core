<?php

namespace SprykerFeature\Zed\Product\Business\Importer\Writer\Void;

use SprykerFeature\Zed\Product\Business\Importer\Model\ConcreteProduct;
use SprykerFeature\Zed\Product\Business\Importer\Writer\ConcreteProductWriterInterface;

/**
 * Class ConcreteProductWriter
 *
 * @package Zed\Product\Component\Importer\Writer\Null
 */
class ConcreteProductWriter implements ConcreteProductWriterInterface
{
    /**
     * @param ConcreteProduct $product
     *
     * @return bool success
     */
    public function writeProduct(ConcreteProduct $product)
    {
        return is_object($product);
    }
}
 