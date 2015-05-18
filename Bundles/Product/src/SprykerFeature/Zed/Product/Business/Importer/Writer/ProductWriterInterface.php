<?php

namespace SprykerFeature\Zed\Product\Business\Importer\Writer;

use SprykerFeature\Shared\Product\Model\AbstractProductInterface;

/**
 * Class GeneraWriter
 *
 * @package Zed\Product\Component\Importer\Writer
 */
interface ProductWriterInterface
{
    /**
     * @param AbstractProductInterface $product
     */
    public function writeProduct(AbstractProductInterface $product);
}