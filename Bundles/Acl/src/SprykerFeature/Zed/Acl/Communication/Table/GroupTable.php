<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Table;

use SprykerFeature\Zed\Acl\Persistence\Propel\Map\SpyAclGroupTableMap;
use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclGroupQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class GroupTable extends AbstractTable
{

    const ROLES = 'roles';
    const EDIT = 'Edit';
    const EDIT_PARAMETER = 'id-group';

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
            self::EDIT => self::EDIT,
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
            ->leftJoinSpyAclGroupsHasRoles()
            ->groupByIdAclGroup()
            ->withColumn('COUNT(fk_acl_role)', self::ROLES)
        ;

        $groupCollection = $this->runQuery($query, $config);

        $groups = [];

        foreach ($groupCollection as $group) {
            $groups[] = [
                SpyAclGroupTableMap::COL_NAME => $group[SpyAclGroupTableMap::COL_NAME],
                SpyAclGroupTableMap::COL_CREATED_AT => $group[SpyAclGroupTableMap::COL_CREATED_AT],
                self::ROLES => $this->createRoleUrl($group),
                self::EDIT => $this->createEditUrl($group),
            ];
        }

        return $groups;
    }

    /**
     * @param array $group
     *
     * @return string
     */
    protected function createRoleUrl(array $group)
    {
        if ($group[self::ROLES] > 0) {
            return '<a href="#" class="display-roles" id="group-'
                . $group[SpyAclGroupTableMap::COL_ID_ACL_GROUP] . '">'
                . $group[self::ROLES] . ' Roles</a> <span class="group-spinner-container" id="group-spinner-'
                . $group[SpyAclGroupTableMap::COL_ID_ACL_GROUP] . '"></span>';
        } else {
            return 'No roles';
        }
    }

    /**
     * @param array $group
     *
     * @return string
     */
    protected function createEditUrl(array $group)
    {
        return sprintf(
            '<a href="/acl/group/edit?%s=%d" class="btn btn-xs btn-primary">Edit</a>',
            self::EDIT_PARAMETER,
            $group[SpyAclGroupTableMap::COL_ID_ACL_GROUP]
        );
    }

}
