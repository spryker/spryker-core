<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\ProductRelation;

use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductRelationWriter implements ProductRelationWriterInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\ProductRelation\ProductRelationDeleterInterface
     */
    protected $productRelationDeleter;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductLabel\Business\ProductRelation\ProductRelationDeleterInterface $productRelationDeleter
     */
    public function __construct(
        ProductLabelQueryContainerInterface $queryContainer,
        ProductRelationDeleterInterface $productRelationDeleter
    ) {
        $this->queryContainer = $queryContainer;
        $this->productRelationDeleter = $productRelationDeleter;
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
