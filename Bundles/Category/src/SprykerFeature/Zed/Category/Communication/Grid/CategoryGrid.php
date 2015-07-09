<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class CategoryGrid extends AbstractGrid
{

    const ID_CATEGORY = 'id_category';
    const NAME = 'name';
    const CATEGORY_IS_ACTIVE = 'is_active';

    /**
     * @return array
     */
    public function definePlugins()
    {
        return [
            $this->createDefaultRowRenderer(),
            $this->createPagination(),
            $this->createDefaultColumn()
                ->setName(self::ID_CATEGORY)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::NAME)
                ->filterable()
                ->sortable(),
            $this->createBooleanColumn()
                ->setName(self::CATEGORY_IS_ACTIVE)
                ->filterable()
                ->sortable(),
        ];
    }

}
