<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewCollector\Dependency\Facade;

interface ProductReviewCollectorToProductReviewInterface
{

    /**
     * @param int $idProductReview
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getCombinedProductReviewImageSets($idProductReview, $idLocale);

}
