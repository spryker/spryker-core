<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder;

use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;

interface CriteriaMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function toCriteria(PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer);
}
