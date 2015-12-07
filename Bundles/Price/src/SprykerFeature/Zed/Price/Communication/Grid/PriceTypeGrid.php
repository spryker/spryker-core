<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Price\Communication\Grid;

use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class PriceTypeGrid extends AbstractTable
{

    const NAME = 'name';

    /**
     * @return void
     */
    protected function configure(TableConfiguration $config)
    {
        // @todo: Implement configure() method.
    }

    /**
     * @return void
     */
    protected function prepareData(TableConfiguration $config)
    {
        // @todo: Implement prepareData() method.
    }

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
