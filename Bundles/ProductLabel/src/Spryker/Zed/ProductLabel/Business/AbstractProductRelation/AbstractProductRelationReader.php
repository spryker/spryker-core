<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\AbstractProductRelation;

use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;

class AbstractProductRelationReader implements AbstractProductRelationReaderInterface
{

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
     * @return int[]
     */
    public function readForProductLabel($idProductLabel)
    {
        $abstractProductIds = [];

        foreach ($this->findRelationEntities($idProductLabel) as $relationEntity) {
            $abstractProductIds[] = (int)$relationEntity->getFkProductAbstract();
        }

        return $abstractProductIds;
    }

    /**
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract[]
     */
    protected function findRelationEntities($idProductLabel)
    {
        return $this
            ->queryContainer
            ->queryAbstractProductRelationsByProductLabel($idProductLabel)
            ->find();
    }

}
