<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\AbstractProductRelation;

use Spryker\Zed\ProductLabel\Business\Touch\AbstractProductRelationTouchManagerInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class AbstractProductRelationWriter implements AbstractProductRelationWriterInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\AbstractProductRelation\AbstractProductRelationDeleterInterface
     */
    protected $productRelationDeleter;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Touch\AbstractProductRelationTouchManagerInterface
     */
    protected $productRelationTouchManager;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductLabel\Business\AbstractProductRelation\AbstractProductRelationDeleterInterface $productRelationDeleter
     * @param AbstractProductRelationTouchManagerInterface $productRelationTouchManager
     */
    public function __construct(
        ProductLabelQueryContainerInterface $queryContainer,
        AbstractProductRelationDeleterInterface $productRelationDeleter,
        AbstractProductRelationTouchManagerInterface $productRelationTouchManager
    ) {
        $this->queryContainer = $queryContainer;
        $this->productRelationDeleter = $productRelationDeleter;
        $this->productRelationTouchManager = $productRelationTouchManager;
    }

    /**
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     *
     * @return void
     */
    public function setRelations($idProductLabel, array $idsProductAbstract)
    {
        $this->handleDatabaseTransaction(function () use ($idProductLabel, $idsProductAbstract) {
            $this->executeSetRelationsTransaction($idProductLabel, $idsProductAbstract);
        });
    }

    /**
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     *
     * @return void
     */
    protected function executeSetRelationsTransaction($idProductLabel, array $idsProductAbstract)
    {
        $this->productRelationDeleter->deleteRelationsForLabel($idProductLabel);

        foreach ($idsProductAbstract as $idProductAbstract) {
            $this->persistRelation($idProductLabel, $idProductAbstract);

            $this->productRelationTouchManager->touchActiveForAbstractProduct($idProductAbstract);
        }
    }

    /**
     * @param int $idProductLabel
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function persistRelation($idProductLabel, $idProductAbstract)
    {
        $relationEntity = $this->createRelationEntity($idProductLabel, $idProductAbstract);
        $relationEntity->save();
    }

    /**
     * @param int $idProductLabel
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract
     */
    protected function createRelationEntity($idProductLabel, $idProductAbstract)
    {
        $relationEntity = $this
            ->queryContainer
            ->queryAbstractProductRelationsByProductLabelAndAbstractProduct(
                $idProductLabel,
                $idProductAbstract
            )
            ->findOneOrCreate();

        $relationEntity->setFkProductLabel($idProductLabel);
        $relationEntity->setFkProductAbstract($idProductAbstract);

        return $relationEntity;
    }

}
