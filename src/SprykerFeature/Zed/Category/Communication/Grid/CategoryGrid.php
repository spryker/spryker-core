<?php

namespace SprykerFeature\Zed\Category\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class CategoryGrid extends AbstractGrid
{

    const ID_CATEGORY = 'id_category';
    const NAME = 'name';
    const CATEGORY_IS_ACTIVE = 'category_is_active';

    /**
     * @return array
     */
    public function definePlugins()
    {
        return [
            $this->locator->ui()->pluginGridDefaultRowsRenderer(),
            $this->locator->ui()->pluginGridPagination(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::ID_CATEGORY)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::NAME)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridBooleanColumn()
                ->setName(self::CATEGORY_IS_ACTIVE)
                ->filterable()
                ->sortable(),
        ];
    }
}
