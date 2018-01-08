<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOption\OptionGroup;

use Generated\Shared\Transfer\StorageProductOptionGroupTransfer;

interface ProductOptionValuePriceReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\StorageProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    public function localizeGroupPrices(StorageProductOptionGroupTransfer $productOptionGroupTransfer);
}
