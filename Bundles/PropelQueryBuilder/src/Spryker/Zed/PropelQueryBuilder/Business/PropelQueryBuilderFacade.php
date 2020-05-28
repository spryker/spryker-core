<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Business;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PropelQueryBuilder\Persistence\PropelQueryBuilderRepositoryInterface getRepository()
 */
class PropelQueryBuilderFacade extends AbstractFacade implements PropelQueryBuilderFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function expandQuery(ModelCriteria $query, QueryCriteriaTransfer $queryCriteriaTransfer): ModelCriteria
    {
        return $this->getRepository()->expandQuery($query, $queryCriteriaTransfer);
    }
}
