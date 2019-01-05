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
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToPermissionFacadeInterface;

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
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToPermissionFacadeInterface
     */
    protected $permissionFacade;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyRoleFacadeInterface $companyRoleFacade
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToPermissionFacadeInterface $permissionFacade
     */
    public function __construct(
        QuoteApprovalToCompanyRoleFacadeInterface $companyRoleFacade,
        QuoteApprovalToCompanyUserFacadeInterface $companyUserFacade,
        QuoteApprovalToPermissionFacadeInterface $permissionFacade
    ) {
        $this->companyRoleFacade = $companyRoleFacade;
        $this->companyUserFacade = $companyUserFacade;
        $this->permissionFacade = $permissionFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getApproversList(QuoteTransfer $quoteTransfer): CompanyUserCollectionTransfer
    {
        $approverIds = $this->getPotentialApproversIds();
        $idBusinessUnit = $quoteTransfer->getCustomer()->getCompanyUserTransfer()->getFkCompanyBusinessUnit();

        $filterTransfer = new CompanyUserCriteriaFilterTransfer();
        $filterTransfer->setCompanyUserIds($approverIds);

        $potentialApproversList = $this->companyUserFacade->getCompanyUserCollection($filterTransfer);

        $potentialApproversList = $this->filterByBusinessUnit($potentialApproversList, $idBusinessUnit);
        $potentialApproversList = $this->filterCompanyUsersWhichCantApproveQuote(
            $potentialApproversList,
            $quoteTransfer
        );

        return $potentialApproversList;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer
     * @param int $idBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    protected function filterByBusinessUnit(
        CompanyUserCollectionTransfer $companyUserCollectionTransfer,
        int $idBusinessUnit
    ): CompanyUserCollectionTransfer {
        foreach ($companyUserCollectionTransfer->getCompanyUsers() as $key => $companyUser) {
            if ($companyUser->getFkCompanyBusinessUnit() !== $idBusinessUnit) {
                $companyUserCollectionTransfer->getCompanyUsers()->offsetUnset($key);
            }
        }

        return $companyUserCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    protected function filterCompanyUsersWhichCantApproveQuote(
        CompanyUserCollectionTransfer $companyUserCollectionTransfer,
        QuoteTransfer $quoteTransfer
    ): CompanyUserCollectionTransfer {
        foreach ($companyUserCollectionTransfer->getCompanyUsers() as $key => $companyUser) {
            if (!$this->permissionFacade->can(ApproveQuotePermissionPlugin::KEY, $companyUser->getIdCompanyUser(), $quoteTransfer)) {
                $companyUserCollectionTransfer->getCompanyUsers()->offsetUnset($key);
            }
        }

        return $companyUserCollectionTransfer;
    }

    /**
     * @return int[]
     */
    protected function getPotentialApproversIds(): array
    {
        return $this->companyRoleFacade->findCompanyUserIdsByPermissionKey(ApproveQuotePermissionPlugin::KEY);
    }
}
