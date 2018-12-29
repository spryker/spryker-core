<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Persistence;

use Orm\Zed\QuoteApproval\Persistence\Map\SpyQuoteApprovalTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalPersistenceFactory getFactory()
 */
class QuoteApprovalRepository extends AbstractRepository implements QuoteApprovalRepositoryInterface
{
    /**
     * @param int $idQuote
     *
     * @return int[]
     */
    public function findQuoteQuoteApprovalIdCollection(int $idQuote): array
    {
        return $this->getFactory()
            ->createQuoteApprovalQuery()
            ->filterByFkQuote($idQuote)
            ->select([SpyQuoteApprovalTableMap::COL_ID_QUOTE_APPROVAL])
            ->find()
            ->toArray();
    }
}
