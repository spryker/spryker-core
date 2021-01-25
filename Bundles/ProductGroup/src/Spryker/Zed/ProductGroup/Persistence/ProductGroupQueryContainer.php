<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductGroup\Persistence\ProductGroupPersistenceFactory getFactory()
 */
class ProductGroupQueryContainer extends AbstractQueryContainer implements ProductGroupQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductGroup
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductGroupQuery
     */
    public function queryProductGroupById($idProductGroup)
    {
        return $this->getFactory()
            ->createProductGroupQuery()
            ->filterByIdProductGroup($idProductGroup);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroupQuery
     */
    public function queryAllProductAbstractGroups()
    {
        return $this->getFactory()
            ->createProductAbstractGroupQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductGroupQuery
     */
    public function queryAllProductGroups()
    {
        return $this->getFactory()
            ->createProductGroupQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductGroup
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroupQuery
     */
    public function queryProductAbstractGroupsById($idProductGroup)
    {
        return $this->getFactory()
            ->createProductAbstractGroupQuery()
            ->filterByFkProductGroup($idProductGroup)
            ->orderByPosition(Criteria::ASC);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param int|null $excludedIdProductGroup
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroupQuery
     */
    public function queryProductAbstractGroupsByIdProductAbstract($idProductAbstract, $excludedIdProductGroup = null)
    {
        $query = $this->getFactory()
            ->createProductAbstractGroupQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->orderByFkProductGroup();

        if ($excludedIdProductGroup) {
            $query->filterByFkProductGroup($excludedIdProductGroup, Criteria::NOT_EQUAL);
        }

        return $query;
    }
}
