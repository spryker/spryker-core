<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Table;

use Orm\Zed\Acl\Persistence\Map\SpyAclGroupTableMap;
use Orm\Zed\Acl\Persistence\SpyAclGroupQuery;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Acl\AclConstants;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class GroupTable extends AbstractTable
{
    /**
     * @var string
     */
    public const ROLES = 'roles';

    /**
     * @var string
     */
    public const EDIT = 'Edit';

    /**
     * @var string
     */
    public const EDIT_PARAMETER = 'id-group';

    /**
     * @var string
     */
    protected const ROOT_GROUP_VISIBLE_NAME = 'Administrators group (%s)';

    /**
     * @var \Orm\Zed\Acl\Persistence\SpyAclGroupQuery
     */
    protected $aclGroupQuery;

    /**
     * @var \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @param \Orm\Zed\Acl\Persistence\SpyAclGroupQuery $aclGroupQuery
     * @param \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct(SpyAclGroupQuery $aclGroupQuery, UtilDateTimeServiceInterface $utilDateTimeService)
    {
        $this->aclGroupQuery = $aclGroupQuery;
        $this->utilDateTimeService = $utilDateTimeService;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->setTableIdentifier('group-table');
        $config->setHeader([
            SpyAclGroupTableMap::COL_NAME => 'Name',
            static::ROLES => 'Roles',
            SpyAclGroupTableMap::COL_CREATED_AT => 'Created At',
            static::EDIT => static::EDIT,
        ]);

        $config->setSearchable([
            SpyAclGroupTableMap::COL_NAME,
        ]);

        $config->setRawColumns([static::EDIT, static::ROLES]);

        $config->setSortable([
            SpyAclGroupTableMap::COL_NAME,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->aclGroupQuery
            ->leftJoinSpyAclGroupsHasRoles()
            ->groupByIdAclGroup()
            ->withColumn('COUNT(fk_acl_role)', static::ROLES);

        $groupCollection = $this->runQuery($query, $config);

        $groups = [];

        foreach ($groupCollection as $group) {
            $groups[] = [
                SpyAclGroupTableMap::COL_NAME => $this->prepareGroupName($group[SpyAclGroupTableMap::COL_NAME]),
                SpyAclGroupTableMap::COL_CREATED_AT => $this->utilDateTimeService->formatDateTime($group[SpyAclGroupTableMap::COL_CREATED_AT]),
                static::ROLES => $this->createRoleUrl($group),
                static::EDIT => $this->createEditUrl($group),
            ];
        }

        return $groups;
    }

    /**
     * @param string $groupName
     *
     * @return string
     */
    protected function prepareGroupName(string $groupName): string
    {
        return $groupName !== AclConstants::ROOT_GROUP ? $groupName : sprintf(static::ROOT_GROUP_VISIBLE_NAME, AclConstants::ROOT_GROUP);
    }

    /**
     * @param array $group
     *
     * @return string
     */
    protected function createRoleUrl(array $group)
    {
        if ($group[static::ROLES] > 0) {
            return '<a href="#" class="display-roles" id="group-'
                . $group[SpyAclGroupTableMap::COL_ID_ACL_GROUP] . '">'
                . $group[static::ROLES] . ' Roles</a> <span class="group-spinner-container" id="group-spinner-'
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
        return $this->generateEditButton(
            Url::generate('/acl/group/edit', [static::EDIT_PARAMETER => $group[SpyAclGroupTableMap::COL_ID_ACL_GROUP]]),
            'Edit',
        );
    }
}
