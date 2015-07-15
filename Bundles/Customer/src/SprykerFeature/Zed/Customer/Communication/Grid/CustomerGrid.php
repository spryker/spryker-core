<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class CustomerGrid extends AbstractGrid
{

    public function definePlugins()
    {
        return [
            $this->createDefaultRowRenderer(),
            $this->createPagination(),
            $this->createDefaultColumn()
                ->setName('id_customer')
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName('email')
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName('first_name')
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName('last_name')
                ->filterable()
                ->sortable(),
        ];
    }

}
