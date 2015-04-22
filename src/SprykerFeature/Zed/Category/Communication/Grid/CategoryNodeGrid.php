<?php

namespace SprykerFeature\Zed\Category\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class CategoryNodeGrid extends AbstractGrid
{

    const ID_CATEGORY_NODE = 'id_category_node';
    const CATEGORY_NAME = 'category_name';
    const PARENT_CATEGORY_NAME = 'parent_category_name';
    const IS_ROOT = 'is_root';

    /**
     * @return array
     */
    public function definePlugins()
    {
        return [
            $this->locator->ui()->pluginGridDefaultRowsRenderer(),
            $this->locator->ui()->pluginGridPagination(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::ID_CATEGORY_NODE)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::CATEGORY_NAME)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::PARENT_CATEGORY_NAME)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridBooleanColumn()
                ->setName(self::IS_ROOT)
                ->filterable()
                ->sortable(),
        ];
    }
}
