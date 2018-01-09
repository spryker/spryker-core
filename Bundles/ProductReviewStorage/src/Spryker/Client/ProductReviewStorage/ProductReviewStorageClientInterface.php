<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReviewStorage;

/**
 * @method \Spryker\Client\ProductReviewStorage\ProductReviewStorageFactory getFactory()
 */
interface ProductReviewStorageClientInterface
{
    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductReviewStorageTransfer
     */
    public function findProductAbstractReview($idProductAbstract);
}
