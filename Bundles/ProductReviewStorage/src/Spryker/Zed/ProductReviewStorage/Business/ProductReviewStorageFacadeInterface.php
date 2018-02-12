<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewStorage\Business;

interface ProductReviewStorageFacadeInterface
{
    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds);

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds);
}
