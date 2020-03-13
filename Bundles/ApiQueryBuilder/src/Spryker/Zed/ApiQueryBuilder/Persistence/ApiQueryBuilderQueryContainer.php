<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiQueryBuilder\Persistence;

use Generated\Shared\Transfer\ApiQueryBuilderQueryTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ApiQueryBuilder\Persistence\ApiQueryBuilderPersistenceFactory getFactory()
 */
class ApiQueryBuilderQueryContainer extends AbstractQueryContainer implements ApiQueryBuilderQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\ApiQueryBuilderQueryTransfer $apiQueryBuilderQueryTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQueryFromRequest(ModelCriteria $query, ApiQueryBuilderQueryTransfer $apiQueryBuilderQueryTransfer)
    {
        return $this->getFactory()
            ->createApiRequestMapper()
            ->buildQuery($query, $apiQueryBuilderQueryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiQueryBuilderQueryTransfer $apiQueryBuilderQueryTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    public function toPropelQueryBuilderCriteria(ApiQueryBuilderQueryTransfer $apiQueryBuilderQueryTransfer)
    {
        return $this->getFactory()
            ->createApiRequestMapper()
            ->toPropelQueryBuilderCriteria($apiQueryBuilderQueryTransfer);
    }
}
