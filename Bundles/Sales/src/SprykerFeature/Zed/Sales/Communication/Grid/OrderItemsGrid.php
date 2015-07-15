<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class OrderItemsGrid extends AbstractGrid
{

    public function definePlugins()
    {
        return [
            $this->createDefaultRowRenderer(),
//            $this->createPagination(),

            $this->createDefaultColumn()
                ->setName('id_sales_order_item')
            ,
            $this->createDefaultColumn()
                ->setName('fk_sales_order')
            ,
            $this->createDefaultColumn()
                ->setName('name')
            ,
            $this->createDefaultColumn()
                ->setName('sku')
            ,
            $this->createDefaultColumn()
                ->setName('ean')
            ,
            $this->createDefaultColumn()
                ->setName('gross_price')
            ,
            $this->createDefaultColumn()
                ->setName('created_at')
            ,
        ];
    }

}
