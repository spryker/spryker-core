<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\AbstractProductRelation;

use Spryker\Zed\ProductLabel\Business\Touch\AbstractProductRelationTouchManagerInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class AbstractProductRelationDeleter implements AbstractProductRelationDeleterInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Touch\AbstractProductRelationTouchManagerInterface
     */
    protected $productRelationTouchManager;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface $queryContainer
     * @param AbstractProductRelationTouchManagerInterface $productRelationTouchManager
     */
    public function __construct(
        ProductLabelQueryContainerInterface $queryContainer,
        AbstractProductRelationTouchManagerInterface $productRelationTouchManager
    ) {
        $this->queryContainer = $queryContainer;
        $this->productRelationTouchManager = $productRelationTouchManager;
    }

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    public function deleteRelationsForLabel($idProductLabel)
    {
        $this->handleDatabaseTransaction(function () use ($idProductLabel) {
            $this->executeDeleteRelationsTransaction($idProductLabel);
        });
    }

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    protected function executeDeleteRelationsTransaction($idProductLabel)
    {
        foreach ($this->findEntitiesForLabel($idProductLabel) as $relationEntity) {
            $relationEntity->delete();

            $this->touchRelationsForAbstractProduct($relationEntity->getFkProductAbstract());
        }
    }

    /**
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract[]
     */
    protected function findEntitiesForLabel($idProductLabel)
    {
        return $this
            ->queryContainer
            ->queryAbstractProductRelationsByProductLabel($idProductLabel)
            ->find();
    }

    /**
     * @param int $idAbstractProduct
     *
     * @return void
     */
    protected function touchRelationsForAbstractProduct($idAbstractProduct)
    {
        if ($this->isEmptyRelationForAbstractProduct($idAbstractProduct)) {
            $this->productRelationTouchManager->touchDeletedForAbstractProduct($idAbstractProduct);

            return;
        }

        $this->productRelationTouchManager->touchActiveForAbstractProduct($idAbstractProduct);
    }

    /**
     * @param int $idAbstractProduct
     *
     * @return bool
     */
    protected function isEmptyRelationForAbstractProduct($idAbstractProduct)
    {
        $relationCount = $this
            ->queryContainer
            ->queryProductLabelByAbstractProduct($idAbstractProduct)
            ->count();

        return ($relationCount === 0);
    }

}
