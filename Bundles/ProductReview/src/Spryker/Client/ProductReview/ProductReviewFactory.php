<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductReview\Zed\ProductReviewStub;

class ProductReviewFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\ProductReview\Zed\ProductReviewStub
     */
    public function createProductReviewStub()
    {
        return new ProductReviewStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient()
    {
        return $this->getProvidedDependency(ProductReviewDependencyProvider::CLIENT_ZED_REQUEST);
    }

}
