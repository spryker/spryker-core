<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductReviewSearch\Business\ProductReviewSearchBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchRepositoryInterface getRepository()
 */
class ProductReviewSearchFacade extends AbstractFacade implements ProductReviewSearchFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productReviewIds
     *
     * @return void
     */
    public function publish(array $productReviewIds)
    {
        $this->getFactory()->createProductReviewWriter()->publish($productReviewIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productReviewIds
     *
     * @return void
     */
    public function unpublish(array $productReviewIds)
    {
        $this->getFactory()->createProductReviewWriter()->unpublish($productReviewIds);
    }
}
