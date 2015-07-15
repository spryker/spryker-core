<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Importer\Writer;

interface ProductWriterInterface
{

    /**
     * @param $product
     */
    public function writeProduct($product);

}
