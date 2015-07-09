<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

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
            $this->createDefaultRowRenderer(),
            $this->createPagination(),
            $this->createDefaultColumn()
                ->setName(self::COLUMN_FOR_STRING)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::COLUMN_FOR_BOOLEAN)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::COLUMN_FOR_TIMESTAMP)
                ->filterable()
                ->sortable(),
        ];

        return $plugins;
    }

}
