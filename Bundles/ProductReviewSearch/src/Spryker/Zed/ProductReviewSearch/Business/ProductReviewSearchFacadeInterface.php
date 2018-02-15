<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Business;

interface ProductReviewSearchFacadeInterface
{
    /**
     * Specification:
     * - Queries all productReview with productReviewIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $productReviewIds
     *
     * @return void
     */
    public function publish(array $productReviewIds);

    /**
     * Specification:
     * - Finds and deletes productReview storage entities with productReviewIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $productReviewIds
     *
     * @return void
     */
    public function unpublish(array $productReviewIds);
}
