<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Dependency;

interface ProductReviewEvents
{
    /**
     * Specification
     * - This events will be used for spy_product_review entity creation
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_REVIEW_CREATE = 'Entity.spy_product_review.create';

    /**
     * Specification
     * - This events will be used for spy_product_review entity update
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_REVIEW_UPDATE = 'Entity.spy_product_review.update';

    /**
     * Specification
     * - This events will be used for spy_product_review entity deletion
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_REVIEW_DELETE = 'Entity.spy_product_review.delete';
}
