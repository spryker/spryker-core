<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Communication\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface;

/**
 * @method \Spryker\Zed\Stock\Business\StockFacade getFacade()
 * @method \Spryker\Zed\Stock\Communication\StockCommunicationFactory getFactory()
 */
class ProductConcreteUpdatePlugin extends AbstractPlugin implements ProductConcretePluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function run(ProductConcreteTransfer $productConcreteTransfer)
    {
        $this->getFacade()->runProductConcreteUpdatePlugin($productConcreteTransfer);
    }

}
