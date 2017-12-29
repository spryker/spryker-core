<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReviewStorage;

use Generated\Shared\Transfer\ProductReviewStorageTransfer;

/**
 * @method ProductReviewStorageFactory getFactory()
 */
interface ProductReviewStorageClientInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return ProductReviewStorageTransfer
     */
    public function findProductAbstractReview($idProductAbstract);
}
