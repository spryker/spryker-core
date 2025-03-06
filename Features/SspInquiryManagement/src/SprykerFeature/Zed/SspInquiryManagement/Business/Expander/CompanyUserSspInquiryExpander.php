<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Expander;

use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;

class CompanyUserSspInquiryExpander implements SspInquiryExpanderInterface
{
    /**
     * @param \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(protected CompanyUserFacadeInterface $companyUserFacade)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): bool
    {
        return $sspInquiryCriteriaTransfer->getInclude() && $sspInquiryCriteriaTransfer->getInclude()->getWithCompanyUser();
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionTransfer $sspInquiryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function expand(SspInquiryCollectionTransfer $sspInquiryCollectionTransfer): SspInquiryCollectionTransfer
    {
        $companyUserCollectionTransfer = $this->companyUserFacade->getCompanyUserCollection(
            (new CompanyUserCriteriaFilterTransfer())->setCompanyUserIds(
                array_map(fn (SspInquiryTransfer $sspInquiryTransfer) => $sspInquiryTransfer->getCompanyUserOrFail()->getIdCompanyUser(), $sspInquiryCollectionTransfer->getSspInquiries()->getArrayCopy()),
            ),
        );

        foreach ($sspInquiryCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
            foreach ($companyUserCollectionTransfer->getCompanyUsers() as $companyUserTransfer) {
                if ($sspInquiryTransfer->getCompanyUserOrFail()->getIdCompanyUser() !== $companyUserTransfer->getIdCompanyUser()) {
                    continue;
                }
                 $sspInquiryTransfer->setCompanyUser($companyUserTransfer);
            }
        }

        return $sspInquiryCollectionTransfer;
    }
}
