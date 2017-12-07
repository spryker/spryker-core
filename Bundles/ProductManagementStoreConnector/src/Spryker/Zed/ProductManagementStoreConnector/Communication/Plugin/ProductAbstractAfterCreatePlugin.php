<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementStoreConnector\Communication\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface;

/**
 * @method \Spryker\Zed\ProductManagementStoreConnector\Business\ProductManagementStoreConnectorFacadeInterface getFacade()
 */
class ProductAbstractAfterCreatePlugin extends AbstractPlugin implements ProductAbstractPluginCreateInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function create(ProductAbstractTransfer $productAbstractTransfer)
    {
        $productAbstractTransfer->getStoreRelation()->setIdEntity($productAbstractTransfer->getIdProductAbstract());

        $this->getFacade()->saveProductAbstractStoreRelation($productAbstractTransfer->getStoreRelation());

        return $productAbstractTransfer;
    }
}
