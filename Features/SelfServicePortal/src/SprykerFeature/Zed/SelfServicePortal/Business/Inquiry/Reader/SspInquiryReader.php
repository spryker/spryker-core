<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Reader;

use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquiryCriteriaExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class SspInquiryReader implements SspInquiryReaderInterface
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $selfServicePortalRepository
     * @param array<int, \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquiryExpanderInterface> $sspInquiryExpanders
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquiryCriteriaExpanderInterface $sspInquiryConditionExpander
     */
    public function __construct(
        protected SelfServicePortalRepositoryInterface $selfServicePortalRepository,
        protected array $sspInquiryExpanders,
        protected SspInquiryCriteriaExpanderInterface $sspInquiryConditionExpander
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer
    {
        $sspInquiryCriteriaTransfer = $this->sspInquiryConditionExpander->expandCriteriaBasedOnCompanyUserPermissions($sspInquiryCriteriaTransfer);

        $sspInquiryCollectionTransfer = $this->selfServicePortalRepository->getSspInquiryCollection($sspInquiryCriteriaTransfer);
        foreach ($this->sspInquiryExpanders as $sspInquiryExpander) {
            if (!$sspInquiryExpander->isApplicable($sspInquiryCriteriaTransfer)) {
                continue;
            }

             $sspInquiryCollectionTransfer = $sspInquiryExpander->expand($sspInquiryCollectionTransfer);
        }

        return $sspInquiryCollectionTransfer;
    }
}
