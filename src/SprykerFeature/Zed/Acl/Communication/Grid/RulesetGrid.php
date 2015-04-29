<?php

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
            $this->locator->ui()->pluginGridDefaultRowsRenderer(),
            $this->locator->ui()->pluginGridPagination(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName('role_name')
                ->filterable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName('type'),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName('bundle')
                ->filterable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName('controller')
                ->filterable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName('action')
                ->filterable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName('has_role'),
        ];

        return $plugins;
    }

}