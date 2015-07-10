<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class AddressGrid extends AbstractGrid
{

    public function definePlugins()
    {
        return [
            $this->createDefaultRowRenderer(),
            $this->createPagination(),
            $this->createDefaultColumn()
                ->setName('id_customer_address')
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
            $this->createDefaultColumn()
                ->setName('address1')
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName('address2')
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName('address3')
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName('company')
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName('city')
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName('comment')
                ->filterable()
                ->sortable(),
        ];
    }

}
