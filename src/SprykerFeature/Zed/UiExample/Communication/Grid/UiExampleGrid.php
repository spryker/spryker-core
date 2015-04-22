<?php

namespace SprykerFeature\Zed\UiExample\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class UiExampleGrid extends AbstractGrid
{

    const COLUMN_FOR_STRING = 'column_for_string';
    const COLUMN_FOR_BOOLEAN = 'column_for_boolean';
    const COLUMN_FOR_TIMESTAMP = 'column_for_timestamp';

    /**
     * @return array
     */
    public function definePlugins()
    {
        $plugins = [
            $this->locator->ui()->pluginGridDefaultRowsRenderer(),
            $this->locator->ui()->pluginGridPagination(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::COLUMN_FOR_STRING)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::COLUMN_FOR_BOOLEAN)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::COLUMN_FOR_TIMESTAMP)
                ->filterable()
                ->sortable()
        ];

        return $plugins;
    }
}
