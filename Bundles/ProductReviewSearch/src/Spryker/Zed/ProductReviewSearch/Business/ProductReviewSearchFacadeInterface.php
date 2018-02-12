<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Business;

interface ProductReviewSearchFacadeInterface
{
    /**
     * @api
     *
     * @param array $productReviewIds
     *
     * @return void
     */
    public function publish(array $productReviewIds);

    /**
     * @api
     *
     * @param array $productReviewIds
     *
     * @return void
     */
    public function unpublish(array $productReviewIds);
}
