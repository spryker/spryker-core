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
     * @param int[] $idsProductAbstract
     *
     * @return void
     */
    public function removeRelations($idProductLabel, array $idsProductAbstract)
    {
        $this->handleDatabaseTransaction(function () use ($idProductLabel, $idsProductAbstract) {
            $this->executeDeleteRelationsTransaction($idProductLabel, $idsProductAbstract);
        });
    }

    /**
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     *
     * @return void
     */
    protected function executeDeleteRelationsTransaction($idProductLabel, array $idsProductAbstract)
    {
        foreach ($idsProductAbstract as $idProductAbstract) {
            $relationEntity = $this->findRelationEntity($idProductLabel, $idProductAbstract);
            $relationEntity->delete();

            $this->touchRelationsForAbstractProduct($relationEntity->getFkProductAbstract());
        }
    }

    /**
     * @param int $idProductLabel
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract
     */
    protected function findRelationEntity($idProductLabel, $idProductAbstract)
    {
        return $this
            ->queryContainer
            ->queryAbstractProductRelationsByProductLabelAndAbstractProduct(
                $idProductLabel,
                $idProductAbstract
            )
            ->findOne();
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
