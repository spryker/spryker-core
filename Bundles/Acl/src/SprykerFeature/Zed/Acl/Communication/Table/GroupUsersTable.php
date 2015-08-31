<?php

namespace SprykerFeature\Zed\Acl\Communication\Table;

use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclGroupQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class GroupsUsersTable extends AbstractTable
{

    protected $groupQuery;

    public function __construct(SpyAclGroupQuery $groupQuery)
    {
        $this->groupQuery = $groupQuery;
    }

    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            'name' => 'Name',
        ]);
    }

    protected function prepareData(TableConfiguration $config)
    {
        $users = $this->runQuery($this->groupQuery, $config);

        return $users;
    }

}
