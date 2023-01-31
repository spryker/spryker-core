<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Business\MessageBroker;

use Generated\Shared\Transfer\AddReviewsTransfer;

interface ProductReviewMessageHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AddReviewsTransfer $addReviewsTransfer
     *
     * @return void
     */
    public function handleAddReviews(AddReviewsTransfer $addReviewsTransfer): void;
}
