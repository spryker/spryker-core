<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\Model;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\Plugin\Permission\ApproveQuotePermissionPlugin;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyRoleFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeInterface;

class PotentialQuoteApproversListProvider implements PotentialQuoteApproversListProviderInterface
{
    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyRoleFacadeInterface
     */
    protected $companyRoleFacade;

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyRoleFacadeInterface $companyRoleFacade
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(
        QuoteApprovalToCompanyRoleFacadeInterface $companyRoleFacade,
        QuoteApprovalToCompanyUserFacadeInterface $companyUserFacade
    ) {
        $this->companyRoleFacade = $companyRoleFacade;
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getApproversList(QuoteTransfer $quoteTransfer): CompanyUserCollectionTransfer
    {
        $approverIds = $this->companyRoleFacade->findCompanyUserIdsByPermissionKey(ApproveQuotePermissionPlugin::KEY);
        $idCompany = $quoteTransfer->getCustomer()->getCompanyUserTransfer()->getFkCompany();

        $filterTransfer = new CompanyUserCriteriaFilterTransfer();

        $filterTransfer->setCompanyUserIds($approverIds);
        $filterTransfer->setIdCompany($idCompany);

        return $this->companyUserFacade->getCompanyUserCollection(
            $filterTransfer
        );
    }
}
