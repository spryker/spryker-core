<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class CommentsGrid extends AbstractGrid
{

    public function definePlugins()
    {
        return [
            $this->createDefaultRowRenderer(),
            $this->createPagination(),

            $this->createDefaultColumn()
                ->setName('id_sales_order_comment')
            ,
            $this->createDefaultColumn()
                ->setName('fk_sales_order')
            ,
            $this->createDefaultColumn()
                ->setName('username')
            ,
            $this->createDefaultColumn()
                ->setName('message')
            ,
            $this->createDefaultColumn()
                ->setName('created_at')
            ,
            $this->createDefaultColumn()
                ->setName('updated_at')
            ,
        ];
    }

}
