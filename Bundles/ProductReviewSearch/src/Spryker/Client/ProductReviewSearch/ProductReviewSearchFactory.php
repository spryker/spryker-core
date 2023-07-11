<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReviewSearch;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductReviewSearch\Builder\ProductReviewKeyBuilder;
use Spryker\Client\ProductReviewSearch\Builder\ProductReviewKeyBuilderInterface;

class ProductReviewSearchFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductReviewSearch\Builder\ProductReviewKeyBuilderInterface
     */
    public function createProductReviewKeyBuilder(): ProductReviewKeyBuilderInterface
    {
        return new ProductReviewKeyBuilder();
    }
}
