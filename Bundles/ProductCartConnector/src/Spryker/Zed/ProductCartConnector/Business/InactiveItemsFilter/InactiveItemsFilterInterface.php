<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Business\InactiveItemsFilter;

use Generated\Shared\Transfer\QuoteTransfer;

interface InactiveItemsFilterInterface
{
    public const MESSAGE_PARAM_SKU = '%sku%';
    public const MESSAGE_INFO_CONCRETE_INACTIVE_PRODUCT_REMOVED = 'product-cart.info.concrete-product-inactive.removed';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterInactiveItems(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
