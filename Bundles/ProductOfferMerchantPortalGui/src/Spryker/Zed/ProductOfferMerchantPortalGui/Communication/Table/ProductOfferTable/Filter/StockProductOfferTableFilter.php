<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\Filter;

use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\SelectTableFilterInterface;

class StockProductOfferTableFilter implements SelectTableFilterInterface
{
    public const FILTER_NAME = 'stock';

    /**
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    public function getFilter(): GuiTableFilterTransfer
    {
        return (new GuiTableFilterTransfer())
            ->setId(static::FILTER_NAME)
            ->setType(static::FILTER_TYPE)
            ->setTitle('Stock')
            ->addTypeOption(static::OPTION_VALUES, $this->getStockOptions());
    }

    /**
     * @return array
     */
    protected function getStockOptions(): array
    {
        return [
            [
                static::OPTION_VALUE_KEY_TITLE => 'Has stock',
                static::OPTION_VALUE_KEY_VALUE => 1,
            ],
            [
                static::OPTION_VALUE_KEY_TITLE => 'Out of stock',
                static::OPTION_VALUE_KEY_VALUE => 0,
            ],
        ];
    }
}
