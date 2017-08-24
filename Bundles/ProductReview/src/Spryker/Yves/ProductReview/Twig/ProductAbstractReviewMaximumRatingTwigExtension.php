<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductReview\Twig;

use Spryker\Client\ProductReview\ProductReviewClientInterface;
use Spryker\Shared\Twig\TwigExtension;
use Spryker\Yves\Kernel\Application;
use Twig_Environment;
use Twig_SimpleFunction;

class ProductAbstractReviewMaximumRatingTwigExtension extends TwigExtension
{

    const FUNCTION_NAME_PRODUCT_ABSTRACT_REVIEW_MAXIMUM_RATING = 'spyProductAbstractReviewMaximumRating';

    /**
     * @var \Spryker\Client\ProductReview\ProductReviewClientInterface
     */
    protected $productReviewClient;

    /**
     * @param \Spryker\Client\ProductReview\ProductReviewClientInterface $productReviewClient
     */
    public function __construct(ProductReviewClientInterface $productReviewClient)
    {
        $this->productReviewClient = $productReviewClient;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction(static::FUNCTION_NAME_PRODUCT_ABSTRACT_REVIEW_MAXIMUM_RATING, [$this, 'renderProductAbstractReviewMaximumRating'], [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]),
        ];
    }

    /**
     * @return string
     */
    public function renderProductAbstractReviewMaximumRating()
    {
        return $this->productReviewClient->getMaximumRating();
    }
}
