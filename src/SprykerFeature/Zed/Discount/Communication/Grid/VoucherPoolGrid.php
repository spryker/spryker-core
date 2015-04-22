<?php

namespace SprykerFeature\Zed\Discount\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class VoucherPoolGrid extends AbstractGrid
{

    const NAME = 'name';
    const CATEGORY = 'category';
    const TEMPLATE = 'template';
    const IS_ACTIVE = 'is_active';
    const IS_INFINITELY_USABLE = 'is_infinitely_usable';

    /**
     * @return array
     */
    public function definePlugins()
    {
        $plugins = [
            $this->locator->ui()->pluginGridDefaultRowsRenderer(),
            $this->locator->ui()->pluginGridPagination(),

            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::NAME)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::CATEGORY)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::TEMPLATE)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridBooleanColumn()
                ->setName(self::IS_ACTIVE)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridBooleanColumn()
                ->setName(self::IS_INFINITELY_USABLE)
                ->filterable()
                ->sortable()
        ];

        return $plugins;
    }
}
