<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\ProductAbstractRelation;

use Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductAbstractRelationDeleter implements ProductAbstractRelationDeleterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface
     */
    protected $productRelationTouchManager;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface $productRelationTouchManager
     */
    public function __construct(
        ProductLabelQueryContainerInterface $queryContainer,
        ProductAbstractRelationTouchManagerInterface $productRelationTouchManager
    ) {
        $this->queryContainer = $queryContainer;
        $this->productRelationTouchManager = $productRelationTouchManager;
    }

    /**
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     * @param bool $isTouchEnabled
     *
     * @return void
     */
    public function removeRelations($idProductLabel, array $idsProductAbstract, bool $isTouchEnabled = true)
    {
        $this->handleDatabaseTransaction(function () use ($idProductLabel, $idsProductAbstract, $isTouchEnabled) {
            $this->executeDeleteRelationsTransaction($idProductLabel, $idsProductAbstract, $isTouchEnabled);
        });
    }

    /**
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     * @param bool $isTouchEnabled
     *
     * @return void
     */
    protected function executeDeleteRelationsTransaction($idProductLabel, array $idsProductAbstract, bool $isTouchEnabled = true)
    {
        foreach ($this->findRelationEntities($idProductLabel, $idsProductAbstract) as $relationEntity) {
            $relationEntity->delete();

            if ($isTouchEnabled) {
                $this->touchRelationsForAbstractProduct($relationEntity->getFkProductAbstract());
            }
        }
    }

    /**
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findRelationEntities($idProductLabel, array $idsProductAbstract)
    {
        return $this
            ->queryContainer
            ->queryProductAbstractRelationsByIdProductLabelAndIdsProductAbstract(
                $idProductLabel,
                $idsProductAbstract
            )
            ->find();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function touchRelationsForAbstractProduct($idProductAbstract)
    {
        if ($this->isEmptyRelationForAbstractProduct($idProductAbstract)) {
            $this->productRelationTouchManager->touchDeletedByIdProductAbstract($idProductAbstract);

            return;
        }

        $this->productRelationTouchManager->touchActiveByIdProductAbstract($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    protected function isEmptyRelationForAbstractProduct($idProductAbstract)
    {
        $relationCount = $this
            ->queryContainer
            ->queryProductsLabelByIdProductAbstract($idProductAbstract)
            ->count();

        return ($relationCount === 0);
    }
}
