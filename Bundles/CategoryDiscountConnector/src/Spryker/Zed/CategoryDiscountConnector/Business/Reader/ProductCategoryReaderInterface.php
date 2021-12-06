<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDiscountConnector\Business\Reader;

use Generated\Shared\Transfer\QuoteTransfer;

interface ProductCategoryReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<int, array<\Generated\Shared\Transfer\ProductCategoryTransfer>>
     */
    public function getProductCategoriesGroupedByIdProductAbstract(QuoteTransfer $quoteTransfer): array;
}
