<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Grid;

use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class RulesetGrid extends AbstractTable
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
                ->setName('role_name')
                ->filterable(),
            $this->createDefaultColumn()
                ->setName('type'),
            $this->createDefaultColumn()
                ->setName('bundle')
                ->filterable(),
            $this->createDefaultColumn()
                ->setName('controller')
                ->filterable(),
            $this->createDefaultColumn()
                ->setName('action')
                ->filterable(),
            $this->createDefaultColumn()
                ->setName('has_role'),
        ];

        return $plugins;
    }

}
