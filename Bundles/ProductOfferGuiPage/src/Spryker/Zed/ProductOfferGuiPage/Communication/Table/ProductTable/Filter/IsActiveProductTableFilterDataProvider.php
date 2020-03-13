<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter;

use Generated\Shared\Transfer\TableFilterTransfer;
use Spryker\Zed\ProductOfferGuiPage\ProductOfferGuiPageConfig;

class IsActiveProductTableFilterDataProvider implements ProductTableFilterDataProviderInterface
{
    protected const OPTION_NAME_YES = 'Yes';
    protected const OPTION_NAME_NO = 'No';

    /**
     * @return \Generated\Shared\Transfer\TableFilterTransfer
     */
    public function getFilterData(): TableFilterTransfer
    {
        return (new TableFilterTransfer())
            ->setKey(ProductOfferGuiPageConfig::PRODUCT_TABLE_IS_ACTIVE_FILTER_NAME)
            ->setTitle('Is Active')
            ->setType('select')
            ->addOption(static::OPTION_NAME_MULTISELECT, false)
            ->addOption(static::OPTION_NAME_VALUES, $this->getIsActiveOptions());
    }

    /**
     * @return int[][]
     */
    protected function getIsActiveOptions(): array
    {
        return [
            [static::OPTION_VALUE_KEY_TITLE => 'Yes', static::OPTION_VALUE_KEY_VALUE => 1],
            [static::OPTION_VALUE_KEY_TITLE => 'No', static::OPTION_VALUE_KEY_VALUE => 2],
        ];
    }
}
