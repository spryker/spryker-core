<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategorySearch\Communication\Expander;

use Generated\Shared\Transfer\MerchantSearchCollectionTransfer;

interface MerchantCategorySearchExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSearchCollectionTransfer
     */
    public function expand(MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer): MerchantSearchCollectionTransfer;
}
