<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductReview;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\ProductReview\Twig\ProductAbstractReviewTwigExtension;

/**
 * @method \Spryker\Client\ProductReview\ProductReviewClientInterface getClient()
 */
class ProductReviewFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Twig\TwigExtension
     */
    public function createProductAbstractReviewTwigExtension()
    {
        return new ProductAbstractReviewTwigExtension($this->getClient(), $this->getLocale());
    }

    /**
     * @return string
     */
    protected function getLocale(): string
    {
        return $this->getProvidedDependency(ProductReviewDependencyProvider::SERVICE_LOCALE);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Yves\Kernel\Application
     */
    protected function getApplication()
    {
        return $this->getProvidedDependency(ProductReviewDependencyProvider::PLUGIN_APPLICATION);
    }
}
