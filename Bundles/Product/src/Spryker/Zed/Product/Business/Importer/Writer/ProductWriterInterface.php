<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Importer\Writer;

interface ProductWriterInterface
{

    /**
     * @param $product
     */
    public function writeProduct($product);

}
