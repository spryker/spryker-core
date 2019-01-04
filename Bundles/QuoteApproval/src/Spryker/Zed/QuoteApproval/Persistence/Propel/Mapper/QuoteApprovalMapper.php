<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Orm\Zed\QuoteApproval\Persistence\SpyQuoteApproval;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeInterface;

class QuoteApprovalMapper implements QuoteApprovalMapperInterface
{
    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(QuoteApprovalToCompanyUserFacadeInterface $companyUserFacade)
    {
        $this->companyUserFacade = $companyUserFacade;
    }

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
        $quoteApprovalEntity->setFkCompanyUser($quoteApprovalTransfer->getApprover()->getIdCompanyUser());
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
        $approver = $this->companyUserFacade->getCompanyUserById($quoteApprovalEntity->getFkCompanyUser());
        $quoteApprovalTransfer->setApprover($approver);

        return $quoteApprovalTransfer;
    }
}
