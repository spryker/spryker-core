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
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class GroupTable extends AbstractTable
{
    public const ROLES = 'roles';
    public const EDIT = 'Edit';
    public const EDIT_PARAMETER = 'id-group';

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
            self::ROLES => 'Roles',
            SpyAclGroupTableMap::COL_CREATED_AT => 'Created At',
            self::EDIT => self::EDIT,
        ]);

        $config->setSearchable([
            SpyAclGroupTableMap::COL_NAME,
        ]);

        $config->setRawColumns([self::EDIT, self::ROLES]);

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
            ->withColumn('COUNT(fk_acl_role)', self::ROLES);

        $groupCollection = $this->runQuery($query, $config);

        $groups = [];

        foreach ($groupCollection as $group) {
            $groups[] = [
                SpyAclGroupTableMap::COL_NAME => $group[SpyAclGroupTableMap::COL_NAME],
                SpyAclGroupTableMap::COL_CREATED_AT => $this->utilDateTimeService->formatDateTime($group[SpyAclGroupTableMap::COL_CREATED_AT]),
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
        return $this->generateEditButton(
            Url::generate('/acl/group/edit', [self::EDIT_PARAMETER => $group[SpyAclGroupTableMap::COL_ID_ACL_GROUP]]),
            'Edit'
        );
    }
}
