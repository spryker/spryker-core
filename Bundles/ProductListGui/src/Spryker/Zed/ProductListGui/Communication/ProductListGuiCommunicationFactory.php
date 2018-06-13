<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductListGui\Communication\Table\ProductListTable;

/**
 * @method \Spryker\Zed\ProductListGui\ProductListGuiConfig getConfig()
 */
class ProductListGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return ProductListTable
     */
    public function createProductListTable(): ProductListTable
    {
        return new ProductListTable();
    }
}
