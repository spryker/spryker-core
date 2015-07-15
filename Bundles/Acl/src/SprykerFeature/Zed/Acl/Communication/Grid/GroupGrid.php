<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class GroupGrid extends AbstractGrid
{

    /**
     * @return array
     */
    public function definePlugins()
    {
        $plugins = [
            $this->createDefaultRowRenderer(),
            $this->createPagination(),
            $this->createDefaultColumn()
                ->setName('id_acl_group')
            ,
            $this->createDefaultColumn()
                ->setName('name')
                ->filterable()
            ,
            $this->createDefaultColumn()
                ->setName('created_at')
            ,
            $this->createDefaultColumn()
                ->setName('updated_at')
            ,
        ];

        return $plugins;
    }

}
