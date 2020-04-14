<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReviewStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductReviewStorage\ProductReviewStorageFactory getFactory()
 */
class ProductReviewStorageClient extends AbstractClient implements ProductReviewStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductReviewStorageTransfer|null
     */
    public function findProductAbstractReview($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductConcreteImageStorageReader()
            ->findProductAbstractReview($idProductAbstract);
    }
}
