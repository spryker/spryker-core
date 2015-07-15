<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class UserGrid extends AbstractGrid
{

    const USERNAME = 'username';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const GROUP_NAME = 'group_name';

    /**
     * @return array
     */
    public function definePlugins()
    {
        $plugins = [
            $this->createDefaultRowRenderer(),
            $this->createPagination(),
            $this->createDefaultColumn()
                ->setName(self::USERNAME)
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
                ->setName(self::GROUP_NAME)
                ->filterable()
                ->sortable(),
        ];

        return $plugins;
    }

}
