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

}
