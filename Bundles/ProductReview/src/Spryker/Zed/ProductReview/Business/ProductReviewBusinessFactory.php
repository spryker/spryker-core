<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductReview\Business\Model\ProductReviewCreator;
use Spryker\Zed\ProductReview\Business\Model\ProductReviewDeleter;
use Spryker\Zed\ProductReview\Business\Model\ProductReviewEntityReader;
use Spryker\Zed\ProductReview\Business\Model\ProductReviewReader;
use Spryker\Zed\ProductReview\Business\Model\ProductReviewStatusUpdater;
use Spryker\Zed\ProductReview\Business\Model\Touch\ProductReviewTouch;
use Spryker\Zed\ProductReview\ProductReviewDependencyProvider;

/**
 * @method \Spryker\Zed\ProductReview\ProductReviewConfig getConfig()
 * @method \Spryker\Zed\ProductReview\Persistence\ProductReviewQueryContainerInterface getQueryContainer()
 */
class ProductReviewBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductReview\Business\Model\ProductReviewCreatorInterface
     */
    public function createProductReviewCreator()
    {
        return new ProductReviewCreator($this->getProvidedDependency(ProductReviewDependencyProvider::CLIENT_PRODUCT_REVIEW));
    }

    /**
     * @return \Spryker\Zed\ProductReview\Business\Model\ProductReviewReaderInterface
     */
    public function createProductReviewReader()
    {
        return new ProductReviewReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductReview\Business\Model\ProductReviewStatusUpdaterInterface
     */
    public function createProductReviewStatusUpdater()
    {
        return new ProductReviewStatusUpdater($this->createProductReviewEntityReader(), $this->createProductReviewTouch());
    }

    /**
     * @return \Spryker\Zed\ProductReview\Business\Model\ProductReviewDeleterInterface
     */
    public function createProductReviewDeleter()
    {
        return new ProductReviewDeleter($this->createProductReviewEntityReader(), $this->createProductReviewTouch());
    }

    /**
     * @return \Spryker\Zed\ProductReview\Business\Model\Touch\ProductReviewTouchInterface
     */
    protected function createProductReviewTouch()
    {
        return new ProductReviewTouch($this->getTouchFacade(), $this->getProductFacade());
    }

    /**
     * @return \Spryker\Zed\ProductReview\Business\Model\ProductReviewEntityReaderInterface
     */
    protected function createProductReviewEntityReader()
    {
        return new ProductReviewEntityReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductReviewDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(ProductReviewDependencyProvider::FACADE_PRODUCT);
    }
}
