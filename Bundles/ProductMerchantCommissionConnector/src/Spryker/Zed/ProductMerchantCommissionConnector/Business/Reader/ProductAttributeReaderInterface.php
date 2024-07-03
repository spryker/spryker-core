<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantCommissionConnector\Business\Reader;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductAttributeReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return array<string, string>
     */
    public function getCombinedConcreteAttributes(
        ProductConcreteTransfer $productConcreteTransfer,
        ?LocaleTransfer $localeTransfer = null
    ): array;

    /**
     * @return list<string>
     */
    public function getProductAttributeKeys(): array;
}
