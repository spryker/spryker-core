<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Business\Expander;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Facade\MerchantRegistrationRequestToCommentFacadeInterface;
use Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestConfig;

class MerchantRegistrationRequestExpander implements MerchantRegistrationRequestExpanderInterface
{
    public function __construct(
        protected MerchantRegistrationRequestToCommentFacadeInterface $commentFacade,
        protected MerchantRegistrationRequestConfig $merchantRegistrationRequestConfig
    ) {
    }

    public function expandMerchantRegistrationRequestWithCommentThread(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationRequestTransfer {
        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerType($this->merchantRegistrationRequestConfig->getCommentThreadOwnerType())
            ->setOwnerId($merchantRegistrationRequestTransfer->getIdMerchantRegistrationRequest());

        return $merchantRegistrationRequestTransfer->setCommentThread(
            $this->commentFacade->findCommentThreadByOwner($commentRequestTransfer),
        );
    }
}
