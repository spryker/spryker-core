<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Grid;

use Propel\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class SalesGrid extends AbstractGrid
{

    const ID_SALES_ORDER = 'id_sales_order';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const NR_OF_ITEMS = 'nr_of_items';
    const GRAND_TOTAL = 'grand_total';

    public function init()
    {
        $this->query->orderByIdSalesOrder(Criteria::DESC);
    }

    public function definePlugins()
    {
        return [
            $this->createDefaultRowRenderer(),
            $this->createPagination(),

            $this->createDefaultColumn()
                ->setName(self::ID_SALES_ORDER)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::FIRST_NAME)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::LAST_NAME)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::NR_OF_ITEMS)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::GRAND_TOTAL)
                ->filterable()
                ->sortable(),

        ];
    }

}
