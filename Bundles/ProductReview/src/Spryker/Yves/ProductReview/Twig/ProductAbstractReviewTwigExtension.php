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

class ProductAbstractReviewTwigExtension extends TwigExtension
{
    public const FUNCTION_NAME_PRODUCT_ABSTRACT_REVIEW = 'spyProductAbstractReview';
    public const FUNCTION_NAME_PRODUCT_ABSTRACT_REVIEW_MAXIMUM_RATING = 'spyProductAbstractReviewMaximumRating';

    /**
     * @var \Spryker\Client\ProductReview\ProductReviewClientInterface
     */
    protected $productReviewClient;

    /**
     * @var \Spryker\Yves\Kernel\Application
     */
    protected $application;

    /**
     * @param \Spryker\Client\ProductReview\ProductReviewClientInterface $productReviewClient
     * @param \Spryker\Yves\Kernel\Application $application
     */
    public function __construct(ProductReviewClientInterface $productReviewClient, Application $application)
    {
        $this->productReviewClient = $productReviewClient;
        $this->application = $application;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            $this->createSpyProductAbstractReviewTwigExtension(),
            $this->createSpyProductAbstractReviewMaximumRatingTwigExtension(),
        ];
    }

    /**
     * @return \Twig_SimpleFunction
     */
    protected function createSpyProductAbstractReviewTwigExtension()
    {
        return new Twig_SimpleFunction(static::FUNCTION_NAME_PRODUCT_ABSTRACT_REVIEW, [$this, 'renderProductAbstractReview'], [
            'is_safe' => ['html'],
            'needs_environment' => true,
        ]);
    }

    /**
     * @return \Twig_SimpleFunction
     */
    protected function createSpyProductAbstractReviewMaximumRatingTwigExtension()
    {
        return new Twig_SimpleFunction(static::FUNCTION_NAME_PRODUCT_ABSTRACT_REVIEW_MAXIMUM_RATING, [$this, 'renderProductAbstractReviewMaximumRating'], [
            'is_safe' => ['html'],
            'needs_environment' => true,
        ]);
    }

    /**
     * @param \Twig_Environment $twig
     * @param int $idProductAbstract
     * @param string $template
     *
     * @return string
     */
    public function renderProductAbstractReview(Twig_Environment $twig, $idProductAbstract, $template)
    {
        $productAbstractReviewTransfer = $this->productReviewClient->findProductAbstractReviewInStorage($idProductAbstract, $this->getLocale());

        if (!$productAbstractReviewTransfer) {
            return '';
        }

        return $twig->render($template, [
            'productAbstractReviewTransfer' => $productAbstractReviewTransfer,
        ]);
    }

    /**
     * @return string
     */
    protected function getLocale()
    {
        return $this->application['locale'];
    }

    /**
     * @return int
     */
    public function renderProductAbstractReviewMaximumRating()
    {
        return $this->productReviewClient->getMaximumRating();
    }
}
