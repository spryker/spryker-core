<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\ProductRelation;

use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductRelationDeleter implements ProductRelationDeleterInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface $queryContainer
     */
    public function __construct(ProductLabelQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
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

}
