<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Stock\Business\StockFacadeInterface getFacade()
 * @method \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Stock\Communication\StockCommunicationFactory getFactory()
 * @method \Spryker\Zed\Stock\Persistence\StockRepositoryInterface getRepository()
 */
class ProductController extends AbstractController
{
    /**
     * @param string $sku
     *
     * @return array
     */
    public function stockAction($sku)
    {
        return $this->viewResponse([
            'productStock' => $this->getFacade()->calculateStockForProduct($sku),
            'isNeverOutOfStock' => $this->getFacade()->isNeverOutOfStock($sku),
        ]);
    }
}
