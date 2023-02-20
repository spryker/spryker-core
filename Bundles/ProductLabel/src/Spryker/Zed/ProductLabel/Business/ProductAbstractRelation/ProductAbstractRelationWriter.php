<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\ProductAbstractRelation;

use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract;
use Spryker\Zed\ProductLabel\Business\Label\Trigger\ProductEventTriggerInterface;
use Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductAbstractRelationWriter implements ProductAbstractRelationWriterInterface
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
     * @var \Spryker\Zed\ProductLabel\Business\Label\Trigger\ProductEventTriggerInterface
     */
    protected ProductEventTriggerInterface $productEventTrigger;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface $productRelationTouchManager
     * @param \Spryker\Zed\ProductLabel\Business\Label\Trigger\ProductEventTriggerInterface $productEventTrigger
     */
    public function __construct(
        ProductLabelQueryContainerInterface $queryContainer,
        ProductAbstractRelationTouchManagerInterface $productRelationTouchManager,
        ProductEventTriggerInterface $productEventTrigger
    ) {
        $this->queryContainer = $queryContainer;
        $this->productRelationTouchManager = $productRelationTouchManager;
        $this->productEventTrigger = $productEventTrigger;
    }

    /**
     * @param int $idProductLabel
     * @param array<int> $idsProductAbstract
     * @param bool $isTouchEnabled
     *
     * @return void
     */
    public function addRelations($idProductLabel, array $idsProductAbstract, bool $isTouchEnabled = true)
    {
        $this->handleDatabaseTransaction(function () use ($idProductLabel, $idsProductAbstract, $isTouchEnabled) {
            $this->executeSetRelationsTransaction($idProductLabel, $idsProductAbstract, $isTouchEnabled);
        });

        $this->productEventTrigger->triggerProductUpdateEvents($idsProductAbstract);
    }

    /**
     * @param int $idProductLabel
     * @param array<int> $idsProductAbstract
     * @param bool $isTouchEnabled
     *
     * @return void
     */
    protected function executeSetRelationsTransaction($idProductLabel, array $idsProductAbstract, bool $isTouchEnabled = true)
    {
        foreach ($idsProductAbstract as $idProductAbstract) {
            $this->persistRelation($idProductLabel, $idProductAbstract);

            if ($isTouchEnabled) {
                $this->productRelationTouchManager->touchActiveByIdProductAbstract($idProductAbstract);
            }
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
