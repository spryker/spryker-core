<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence;

use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\PropelQueryBuilder\Persistence\PropelQueryBuilderPersistenceFactory getFactory()
 */
class PropelQueryBuilderRepository extends AbstractRepository implements PropelQueryBuilderRepositoryInterface
{
    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function expandQuery(ModelCriteria $query, QueryCriteriaTransfer $queryCriteriaTransfer): ModelCriteria
    {
        $propelQueryBuilderCriteriaTransfer = $this->getFactory()
            ->createCriteriaQueryMapper()
            ->mapQueryCriteriaTransferToPropelQueryBuilderCriteriaTransfer(
                $queryCriteriaTransfer,
                new PropelQueryBuilderCriteriaTransfer()
            );

        return $this->getFactory()
            ->createQueryBuilder()
            ->buildQuery($query, $propelQueryBuilderCriteriaTransfer);
    }
}
