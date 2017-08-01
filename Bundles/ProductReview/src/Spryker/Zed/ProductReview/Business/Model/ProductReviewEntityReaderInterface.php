<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Business\Model;

interface ProductReviewEntityReaderInterface
{

    /**
     * @param int $idProductReview
     *
     * @throws \Exception
     *
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReview
     */
    public function getProductReviewEntityById($idProductReview);

}
