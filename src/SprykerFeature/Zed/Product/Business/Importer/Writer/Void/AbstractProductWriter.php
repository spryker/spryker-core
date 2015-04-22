<?php

namespace SprykerFeature\Zed\Product\Business\Importer\Writer\Void;

use SprykerFeature\Zed\Product\Business\Importer\Writer\AbstractProductWriterInterface;
use SprykerFeature\Zed\Product\Business\Importer\Model\AbstractProduct;

/**
 * Class AbstractProductWriter
 *
 * @package SprykerFeature\Zed\Product\Business\Importer\Writer\Void
 */
class AbstractProductWriter implements AbstractProductWriterInterface
{
    /**
     * @param AbstractProduct $product
     *
     * @return bool
     */
    public function writeAbstractProduct(AbstractProduct $product)
    {
        return is_object($product);
    }
}
 