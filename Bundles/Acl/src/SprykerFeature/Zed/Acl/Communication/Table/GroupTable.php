<?php

namespace SprykerFeature\Zed\Acl\Communication\Table;

use SprykerFeature\Zed\Acl\Persistence\Propel\Map\SpyAclGroupTableMap;
use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclGroupQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class GroupTable extends AbstractTable
{

    const ROLES = 'roles';

    /**
     * @var SpyAclGroupQuery
     */
    protected $aclGroupQuery;

    /**
     * @param SpyAclGroupQuery $aclGroupQuery
     */
    public function __construct(SpyAclGroupQuery $aclGroupQuery)
    {
        $this->aclGroupQuery = $aclGroupQuery;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->setTableIdentifier('group-table');
        $config->setHeader([
            SpyAclGroupTableMap::COL_NAME => 'Name',
            self::ROLES => 'Roles',
            SpyAclGroupTableMap::COL_CREATED_AT => 'Created At',
        ]);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->aclGroupQuery
            ->leftJoinSpyAclGroupsHasRoles('has_roles')
            ->groupByIdAclGroup()
            ->withColumn('COUNT(*)', self::ROLES)
        ;

        $groupsResult = $this->runQuery($query, $config);

        $groups = [];

        foreach ($groupsResult as $group) {
            $groups[] = [
                SpyAclGroupTableMap::COL_NAME => $group[SpyAclGroupTableMap::COL_NAME],
                SpyAclGroupTableMap::COL_CREATED_AT => $group[SpyAclGroupTableMap::COL_CREATED_AT],
                self::ROLES => $this->createRolesUrl($group),
            ];
        }

        return $groups;
    }

    /**
     * @param array $group
     *
     * @return string
     */
    protected function createRolesUrl(array $group)
    {
        return '<a href="#" class="display-roles" id="group-' . $group[SpyAclGroupTableMap::COL_ID_ACL_GROUP] . '">' . $group[self::ROLES] . ' Roles</a>';
//        return '<a data-toggle="modal" data-taget="groupsModal" href="/acl/group/roles?id-group=' . $group[SpyAclGroupTableMap::COL_ID_ACL_GROUP] . '">' . $group[self::ROLES] . ' Roles</a>';
    }

}
