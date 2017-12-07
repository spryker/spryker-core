<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementStoreConnector\Communication\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Business\Product\Observer\ProductAbstractReadObserverInterface;

/**
 * @method \Spryker\Zed\ProductManagementStoreConnector\Business\ProductManagementStoreConnectorFacadeInterface getFacade()
 */
class ProductAbstractReadPlugin extends AbstractPlugin implements ProductAbstractReadObserverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function read(ProductAbstractTransfer $productAbstractTransfer)
    {
        $productAbstractTransfer->setStoreRelation(
            $this->getStoreRelation($productAbstractTransfer->getIdProductAbstract())
        );

        return $productAbstractTransfer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function getStoreRelation($idProductAbstract)
    {
        return $this->getFacade()->getProductAbstractStoreRelation(
            (new StoreRelationTransfer())
                ->setIdEntity($idProductAbstract)
        );
    }
}
