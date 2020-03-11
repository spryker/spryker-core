<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter;

use Generated\Shared\Transfer\TableFilterTransfer;

interface ProductTableFilterDataProviderInterface
{
    public const OPTION_NAME_MULTISELECT = 'multiselect';
    public const OPTION_NAME_VALUES = 'values';

    public const OPTION_VALUE_KEY_VALUE = 'value';
    public const OPTION_VALUE_KEY_TITLE = 'title';

    /**
     * @return \Generated\Shared\Transfer\TableFilterTransfer
     */
    public function getFilterData(): TableFilterTransfer;
}
