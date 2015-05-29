<?php

namespace SprykerFeature\Zed\Product\Business\Importer\Writer;

interface ProductWriterInterface
{
    /**
     * @param $product
     */
    public function writeProduct($product);
}