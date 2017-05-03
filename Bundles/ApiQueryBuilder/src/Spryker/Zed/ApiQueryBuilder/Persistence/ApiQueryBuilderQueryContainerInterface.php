<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiQueryBuilder\Persistence;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderTableTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

/**
 * @method \Spryker\Zed\ApiQueryBuilder\Persistence\ApiQueryBuilderPersistenceFactory getFactory()
 */
interface ApiQueryBuilderQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderTableTransfer $tableTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQueryFromRequest(
        ApiRequestTransfer $apiRequestTransfer,
        ModelCriteria $query,
        PropelQueryBuilderTableTransfer $tableTransfer
    );

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\PropelQueryBuilderTableTransfer $tableTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    public function toPropelQueryBuilderCriteria(
        ApiRequestTransfer $apiRequestTransfer,
        PropelQueryBuilderTableTransfer $tableTransfer
    );

}
