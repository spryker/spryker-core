<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Dependency;

interface ProductReviewEvents
{
    /**
     * Specification
     * - This events will be used for spy_product_review publishing
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_ABSTRACT_REVIEW_PUBLISH = 'ProductReview.product_abstract_review.publish';

    /**
     * Specification
     * - This events will be used for spy_product_review un-publishing
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_ABSTRACT_REVIEW_UNPUBLISH = 'ProductReview.product_abstract_review.unpublish';

    /**
     * Specification
     * - This events will be used for product_review publishing
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_REVIEW_PUBLISH = 'ProductReview.product_review.publish';

    /**
     * Specification
     * - This events will be used for product_review un-publishing
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_REVIEW_UNPUBLISH = 'ProductReview.product_review.unpublish';

    /**
     * Specification
     * - This events will be used for spy_product_review entity creation
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_REVIEW_CREATE = 'Entity.spy_product_review.create';

    /**
     * Specification
     * - This events will be used for spy_product_review entity update
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_REVIEW_UPDATE = 'Entity.spy_product_review.update';

    /**
     * Specification
     * - This events will be used for spy_product_review entity deletion
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_REVIEW_DELETE = 'Entity.spy_product_review.delete';

    /**
     * @uses \Spryker\Zed\Product\Dependency\ProductEvents::PRODUCT_CONCRETE_UPDATE
     *
     * @var string
     */
    public const PRODUCT_CONCRETE_UPDATE = 'Product.product_concrete.update';
}
