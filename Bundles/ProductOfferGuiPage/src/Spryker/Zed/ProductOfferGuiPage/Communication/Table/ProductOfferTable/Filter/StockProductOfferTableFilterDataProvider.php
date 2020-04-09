<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\Filter;


use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\Filter\TableFilterDataProviderInterface;

class StockProductOfferTableFilterDataProvider implements TableFilterDataProviderInterface
{
    public const FILTER_NAME = 'stock';

    public const OPTION_ALWAYS_IN_STOCK_VALUE = 2;
    public const OPTION_HAS_STOCK_VALUE = 1;
    public const OPTION_OUT_OF_STOCK_VALUE = 0;

    protected const OPTION_ALWAYS_IN_STOCK = 'Always in stock';
    protected const OPTION_HAS_STOCK = 'Has stock';
    protected const OPTION_OUT_OF_STOCK = 'Out of stock';

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
                static::OPTION_VALUE_KEY_TITLE => static::OPTION_ALWAYS_IN_STOCK,
                static::OPTION_VALUE_KEY_VALUE => static::OPTION_ALWAYS_IN_STOCK_VALUE,
            ],
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