<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductReviewStorage\Business\Storage\ProductReviewStorageWriter;

/**
 * @method \Spryker\Zed\ProductReviewStorage\ProductReviewStorageConfig getConfig()
 * @method \Spryker\Zed\ProductReviewStorage\Persistence\ProductReviewStorageQueryContainerInterface getQueryContainer()
 */
class ProductReviewStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductReviewStorage\Business\Storage\ProductReviewStorageWriterInterface
     */
    public function createProductReviewStorageWriter()
    {
        return new ProductReviewStorageWriter(
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue()
        );
    }
}
