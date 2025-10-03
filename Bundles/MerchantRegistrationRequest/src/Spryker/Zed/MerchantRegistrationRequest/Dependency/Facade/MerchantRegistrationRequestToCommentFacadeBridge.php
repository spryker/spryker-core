<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Dependency\Facade;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;

class MerchantRegistrationRequestToCommentFacadeBridge implements MerchantRegistrationRequestToCommentFacadeInterface
{
    /**
     * @var \Spryker\Zed\Comment\Business\CommentFacadeInterface
     */
    protected $commentFacade;

    /**
     * @param \Spryker\Zed\Comment\Business\CommentFacadeInterface $commentFacade
     */
    public function __construct($commentFacade)
    {
        $this->commentFacade = $commentFacade;
    }

    public function findCommentThreadByOwner(CommentRequestTransfer $commentRequestTransfer): ?CommentThreadTransfer
    {
        return $this->commentFacade->findCommentThreadByOwner($commentRequestTransfer);
    }
}
