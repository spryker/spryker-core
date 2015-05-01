<?php

namespace SprykerFeature\Zed\Product\Business\Importer\Writer;

use SprykerFeature\Zed\Product\Business\Importer\Model\ConcreteProduct;

/**
 * Interface ProductWriterInterface
 *
 * @package Zed\Product\Component\Importer\Writer
 */
interface ConcreteProductWriterInterface
{
    /**
     * @param ConcreteProduct $product
     *
     * @return bool success
     */
    public function writeProduct(ConcreteProduct $product);
}