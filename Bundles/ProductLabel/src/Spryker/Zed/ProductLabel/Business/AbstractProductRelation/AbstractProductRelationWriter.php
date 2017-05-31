<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\AbstractProductRelation;

use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract;
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
     * @var \Spryker\Zed\ProductLabel\Business\Touch\AbstractProductRelationTouchManagerInterface
     */
    protected $productRelationTouchManager;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductLabel\Business\Touch\AbstractProductRelationTouchManagerInterface $productRelationTouchManager
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
    public function addRelations($idProductLabel, array $idsProductAbstract)
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
        $relationEntity = new SpyProductLabelProductAbstract();
        $relationEntity->setFkProductLabel($idProductLabel);
        $relationEntity->setFkProductAbstract($idProductAbstract);

        return $relationEntity;
    }

}
