<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReviewSearch\Builder;

interface ProductReviewKeyBuilderInterface
{
    /**
     * @param string $idProductReview
     *
     * @return string
     */
    public function buildKey(string $idProductReview): string;
}
