<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductBarcodeGui\Communication\Table\ProductBarcodeTable;

class ProductBarcodeGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductBarcodeGui\Communication\Table\ProductBarcodeTable
     */
    public function createProductBarcodeTable(): ProductBarcodeTable
    {
        return new ProductBarcodeTable();
    }
}
