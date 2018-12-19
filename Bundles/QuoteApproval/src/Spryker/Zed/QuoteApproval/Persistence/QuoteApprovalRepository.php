<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Persistence;

use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalPersistenceFactory getFactory()
 */
class QuoteApprovalRepository extends AbstractRepository implements QuoteApprovalRepositoryInterface
{
    /**
     * @param int $idQuoteApproval
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer
     */
    public function findQuoteApprovalById(int $idQuoteApproval): QuoteApprovalTransfer
    {
        $spyQuoteApproval = $this->getFactory()
            ->createSpyQuoteApprovalQuery()
            ->findOneByIdQuoteApproval($idQuoteApproval);

        $quoteApprovalTransfer = $this->getFactory()
            ->createQuoteApprovalMapper()
            ->mapEntityToQuoteApprovalTransfer($spyQuoteApproval, new QuoteApprovalTransfer());

        return $quoteApprovalTransfer;
    }
}
