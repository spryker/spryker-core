<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Orm\Zed\QuoteApproval\Persistence\SpyQuoteApproval;

class QuoteApprovalMapper
{
    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     * @param \Orm\Zed\QuoteApproval\Persistence\SpyQuoteApproval $quoteApprovalEntity
     *
     * @return \Orm\Zed\QuoteApproval\Persistence\SpyQuoteApproval
     */
    public function mapQuoteApprovalTransferToEntity(
        QuoteApprovalTransfer $quoteApprovalTransfer,
        SpyQuoteApproval $quoteApprovalEntity
    ): SpyQuoteApproval {
        if ($quoteApprovalTransfer->getApproverCompanyUserId()) {
            $quoteApprovalEntity->setFkCompanyUser($quoteApprovalTransfer->getApproverCompanyUserId());
        }
        $quoteApprovalEntity->setFkQuote($quoteApprovalTransfer->getFkQuote());
        $quoteApprovalEntity->setStatus($quoteApprovalTransfer->getStatus());

        return $quoteApprovalEntity;
    }

    /**
     * @param \Orm\Zed\QuoteApproval\Persistence\SpyQuoteApproval $quoteApprovalEntity
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer
     */
    public function mapQuoteApprovalEntityToTransfer(
        SpyQuoteApproval $quoteApprovalEntity,
        QuoteApprovalTransfer $quoteApprovalTransfer
    ): QuoteApprovalTransfer {
        $quoteApprovalTransfer->fromArray($quoteApprovalEntity->toArray(), true);
        $companyUserEntity = $quoteApprovalEntity->getSpyCompanyUser();

        $companyUserTransfer = (new CompanyUserTransfer())->fromArray(
            $companyUserEntity->toArray(),
            true
        );

        $customerTransfer = (new CustomerTransfer())->fromArray(
            $companyUserEntity->getCustomer()->toArray(),
            true
        );

        $companyUserTransfer->setCustomer($customerTransfer);
        $quoteApprovalTransfer->setApprover($companyUserTransfer);
        $quoteApprovalTransfer->setApproverCompanyUserId($quoteApprovalEntity->getFkCompanyUser());

        return $quoteApprovalTransfer;
    }
}
