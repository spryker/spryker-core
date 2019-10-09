<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Expander;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;

interface ProductReviewsAbstractProductsResourceExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer
     */
    public function expand(AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer): AbstractProductsRestAttributesTransfer;
}
