<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

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
            $this->createDefaultRowRenderer(),
            $this->createPagination(),
            $this->createDefaultColumn()
                ->setName(self::ID_CATEGORY_NODE)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::CATEGORY_NAME)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::PARENT_CATEGORY_NAME)
                ->filterable()
                ->sortable(),
            $this->createBooleanColumn()
                ->setName(self::IS_ROOT)
                ->filterable()
                ->sortable(),
        ];
    }

}
