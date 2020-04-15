<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\Filter;

use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface;

class StockProductOfferTableFilterDataProvider implements TableFilterDataProviderInterface
{
    protected const FILTER_NAME = 'stock';

    protected const OPTION_HAS_STOCK = 'Has stock';
    protected const OPTION_OUT_OF_STOCK = 'Out of stock';

    protected const OPTION_HAS_STOCK_VALUE = 1;
    protected const OPTION_OUT_OF_STOCK_VALUE = 0;

    /**
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    public function getFilterData(): GuiTableFilterTransfer
    {
        return (new GuiTableFilterTransfer())
            ->setId(static::FILTER_NAME)
            ->setTitle('Stock')
            ->setType('select')
            ->addTypeOption(static::OPTION_NAME_MULTISELECT, false)
            ->addTypeOption(static::OPTION_NAME_VALUES, $this->getStockOptions());
    }

    /**
     * @return array
     */
    protected function getStockOptions(): array
    {
        return [
            [
                static::OPTION_VALUE_KEY_TITLE => static::OPTION_HAS_STOCK,
                static::OPTION_VALUE_KEY_VALUE => static::OPTION_HAS_STOCK_VALUE,
            ],
            [
                static::OPTION_VALUE_KEY_TITLE => static::OPTION_OUT_OF_STOCK,
                static::OPTION_VALUE_KEY_VALUE => static::OPTION_OUT_OF_STOCK_VALUE,
            ],
        ];
    }
}
