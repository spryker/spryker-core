<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class RulesetGrid extends AbstractGrid
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
