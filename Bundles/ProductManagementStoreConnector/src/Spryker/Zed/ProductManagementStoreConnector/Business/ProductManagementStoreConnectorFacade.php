<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementStoreConnector\Business;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductManagementStoreConnector\Business\ProductManagementStoreConnectorBusinessFactory getFactory()
 */
class ProductManagementStoreConnectorFacade extends AbstractFacade implements ProductManagementStoreConnectorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelation
     *
     * @return void
     */
    public function saveProductAbstractStoreRelation(StoreRelationTransfer $storeRelation)
    {
        $this->getFactory()
            ->createProductAbstractStoreRelationSaver()
            ->save($storeRelation);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getProductAbstractStoreRelation(StoreRelationTransfer $storeRelationTransfer)
    {
        return $this->getFactory()
            ->createProductAbstractStoreRelationReader()
            ->getStoreRelation($storeRelationTransfer);
    }
}
