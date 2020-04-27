<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter;

interface SelectTableFilterInterface extends TableFilterInterface
{
    public const FILTER_TYPE = 'select';

    public const OPTION_MULTIPLE = 'multiselect';
    public const OPTION_VALUES = 'values';
    public const OPTION_VALUE_KEY_VALUE = 'value';
    public const OPTION_VALUE_KEY_TITLE = 'title';
}
