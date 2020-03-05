<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter;

use Generated\Shared\Transfer\TableFilterTransfer;

interface ProductListTableFilterDataProviderInterface
{
    /**
     * @return \Generated\Shared\Transfer\TableFilterTransfer
     */
    public function getFilterData(): TableFilterTransfer;
}
