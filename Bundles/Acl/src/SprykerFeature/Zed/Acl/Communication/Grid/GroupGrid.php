<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Grid;

use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class GroupGrid extends AbstractTable
{

    protected function configure(TableConfiguration $config)
    {
        // @todo: Implement configure() method.
    }

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
