<?php

namespace SprykerFeature\Zed\Product\Business\Importer\Writer;

use SprykerFeature\Shared\Product\Model\ProductInterface;

/**
 * Class GeneraWriter
 *
 * @package Zed\Product\Component\Importer\Writer
 */
interface ProductWriterInterface
{
    /**
     * @param ProductInterface $product
     */
    public function writeProduct(ProductInterface $product);
}