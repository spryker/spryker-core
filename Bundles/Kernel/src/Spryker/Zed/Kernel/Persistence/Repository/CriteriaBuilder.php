<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence\Repository;

use Generated\Shared\Transfer\FilterTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class CriteriaBuilder implements CriteriaBuilderInterface
{
    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \Generated\Shared\Transfer\FilterTransfer|null $filterTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQueryFromCriteria(ModelCriteria $modelCriteria, ?FilterTransfer $filterTransfer = null)
    {
        $criteria = $modelCriteria->setFormatter(TransferObjectFormatter::class);

        if (!$filterTransfer) {
            return $criteria;
        }

        if ($filterTransfer->getLimit()) {
            $criteria->setLimit($filterTransfer->getLimit());
        }

        if ($filterTransfer->getOffset()) {
            $criteria->setOffset($filterTransfer->getOffset());
        }

        if ($filterTransfer->getOrderBy() && $filterTransfer->getOrderDirection()) {
            $criteria->orderBy($filterTransfer->getOrderBy(), $filterTransfer->getOrderDirection());
        }

        return $criteria;
    }
}
