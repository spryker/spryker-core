<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander;

use Generated\Shared\Transfer\CommentsRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Spryker\Zed\Comment\Business\CommentFacadeInterface;

class CommentsSspInquiryExpander implements SspInquiryExpanderInterface
{
    public function __construct(protected CommentFacadeInterface $commentFacade)
    {
    }

    public function expand(SspInquiryCollectionTransfer $sspInquiryCollectionTransfer): SspInquiryCollectionTransfer
    {
        foreach ($sspInquiryCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
            $commentThreadTransfers = $this->commentFacade->getCommentThreads(
                (new CommentsRequestTransfer())
                    ->setOwnerType('ssp-inquiry-internal')
                    ->addOwnerId($sspInquiryTransfer->getIdSspInquiry()),
            );

            if (!$commentThreadTransfers) {
                continue;
            }
             $sspInquiryTransfer->setInternalCommentThread(reset($commentThreadTransfers));
        }

        return $sspInquiryCollectionTransfer;
    }

    public function isApplicable(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): bool
    {
        return $sspInquiryCriteriaTransfer->getInclude() && $sspInquiryCriteriaTransfer->getInclude()->getWithComments();
    }
}
