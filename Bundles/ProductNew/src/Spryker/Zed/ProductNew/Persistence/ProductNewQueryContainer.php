<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductNew\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductNew\Persistence\ProductNewPersistenceFactory getFactory()
 */
class ProductNewQueryContainer extends AbstractQueryContainer implements ProductNewQueryContainerInterface
{

    /**
     * @api
     *
     * @param string $labelName
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelByName($labelName)
    {
        return $this->getFactory()
            ->getProductLabelQueryContainer()
            ->queryProductLabelByName($labelName);
    }

    /**
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryRelationsBecomingInactive($idProductLabel)
    {
        return $this->getFactory()
            ->getProductLabelQueryContainer()
            ->queryProductAbstractRelationsByIdProductLabel($idProductLabel)
            ->useSpyProductAbstractQuery()
                ->filterByNewTo('now', Criteria::LESS_THAN)
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryRelationsBecomingActive($idProductLabel)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
            ->filterByNewFrom('now', Criteria::LESS_EQUAL)
            ->filterByNewTo('now', Criteria::GREATER_THAN)
            ->useSpyProductLabelProductAbstractQuery('rel', Criteria::LEFT_JOIN)
                ->filterByFkProductLabel(null, Criteria::ISNULL)
            ->endUse()
            ->addJoinCondition('rel', sprintf('rel.fk_product_label = %d', $idProductLabel));
    }

}
