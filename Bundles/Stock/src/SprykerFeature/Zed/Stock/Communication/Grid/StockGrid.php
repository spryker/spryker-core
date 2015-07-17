<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Stock\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class StockGrid extends AbstractGrid
{

    const NAME = 'name';

    /**
     * @return array
     */
    public function definePlugins()
    {
        $plugins = [
            $this->createDefaultRowRenderer(),
            $this->createPagination(),
            $this->createDefaultColumn()
                ->setName(self::NAME)
                ->filterable()
                ->sortable(),
        ];

        return $plugins;
    }

}
