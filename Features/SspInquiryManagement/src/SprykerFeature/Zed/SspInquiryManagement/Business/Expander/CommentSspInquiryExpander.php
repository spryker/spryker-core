<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Expander;

use Generated\Shared\Transfer\CommentsRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Spryker\Zed\Comment\Business\CommentFacadeInterface;

class CommentSspInquiryExpander implements SspInquiryExpanderInterface
{
    /**
     * @param \Spryker\Zed\Comment\Business\CommentFacadeInterface $commentFacade
     */
    public function __construct(protected CommentFacadeInterface $commentFacade)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionTransfer $sspInquiryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function expand(SspInquiryCollectionTransfer $sspInquiryCollectionTransfer): SspInquiryCollectionTransfer
    {
         $sspInquiryIds = [];

        foreach ($sspInquiryCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
             $sspInquiryIds[] = $sspInquiryTransfer->getIdSspInquiry();
        }

        $commentThreadTransfers = $this->commentFacade->getCommentThreads(
            (new CommentsRequestTransfer())
                ->setOwnerType('ssp-inquiry-internal')
                ->setOwnerIds($sspInquiryIds),
        );

        if (!count($commentThreadTransfers)) {
            return $sspInquiryCollectionTransfer;
        }

        foreach ($sspInquiryCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
            foreach ($commentThreadTransfers as $commentThreadTransfer) {
                if ($sspInquiryTransfer->getIdSspInquiry() !== $commentThreadTransfer->getOwnerId()) {
                    continue;
                }

                 $sspInquiryTransfer->setInternalCommentThread($commentThreadTransfer);
            }
        }

        return $sspInquiryCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): bool
    {
        return !$sspInquiryCriteriaTransfer->getInclude() || $sspInquiryCriteriaTransfer->getInclude()->getWithComments();
    }
}
