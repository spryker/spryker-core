<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementStoreConnector\Business\Model\ProductAbstractStoreRelation;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\ProductManagementStoreConnector\Persistence\ProductManagementStoreConnectorQueryContainerInterface;

class ProductAbstractStoreRelationReader implements ProductAbstractStoreRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductManagementStoreConnector\Persistence\ProductManagementStoreConnectorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\ProductManagementStoreConnector\Persistence\ProductManagementStoreConnectorQueryContainerInterface $queryContainer
     */
    public function __construct(ProductManagementStoreConnectorQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelation(StoreRelationTransfer $storeRelationTransfer)
    {
        $storeRelationTransfer->setIdStores(
            $this->getIdStores($storeRelationTransfer->getIdEntity())
        );

        return $storeRelationTransfer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    protected function getIdStores($idProductAbstract)
    {
        $productAbstractStoreCollection = $this->queryContainer
            ->queryProductAbstractStoreByFkProductAbstract($idProductAbstract)
            ->find();

        $idStores = [];
        foreach ($productAbstractStoreCollection as $productAbstractStoreEntity) {
            $idStores[] = $productAbstractStoreEntity->getFkStore();
        }

        return $idStores;
    }
}
