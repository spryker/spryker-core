<?php

namespace SprykerFeature\Zed\Product\Business\Importer\Writer;

use SprykerFeature\Zed\Product\Business\Importer\Model\AbstractProduct;

/**
 * Interface AbstractProductWriterInterface
 *
 * @package SprykerFeature\Zed\Product\Business\Importer\Writer
 */
interface AbstractProductWriterInterface
{
    /**
     * @param AbstractProduct $product
     *
     * @return bool
     */
    public function writeAbstractProduct(AbstractProduct $product);
}