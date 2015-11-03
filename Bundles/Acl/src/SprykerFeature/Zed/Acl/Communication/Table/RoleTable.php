<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Table;

use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;
use Orm\Zed\Acl\Persistence\Map\SpyAclRoleTableMap;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\Acl\AclConfig;

class RoleTable extends AbstractTable
{

    const ACTION = 'Action';
    const UPDATE_ROLE_URL = '/acl/role/update?id-role=%d';
    const DELETE_ROLE_URL = '/acl/role/delete?id-role=%d';

    /**
     * @var AclQueryContainer
     */
    private $aclQueryContainer;

    /**
     * @param AclQueryContainer $aclQueryContainer
     */
    public function __construct(AclQueryContainer $aclQueryContainer)
    {
        $this->aclQueryContainer = $aclQueryContainer;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyAclRoleTableMap::COL_CREATED_AT => 'Created at',
            SpyAclRoleTableMap::COL_NAME => 'Name',
            self::ACTION => self::ACTION,
        ]);

        $config->setSortable([
            SpyAclRoleTableMap::COL_CREATED_AT,
            SpyAclRoleTableMap::COL_NAME,
        ]);

        $config->setSearchable([
            SpyAclRoleTableMap::COL_NAME,
        ]);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return mixed
     */
    protected function prepareData(TableConfiguration $config)
    {
        $roleQuery = $this->aclQueryContainer->queryRole();
        $queryResults = $this->runQuery($roleQuery, $config);

        $results = [];
        foreach ($queryResults as $rule) {
            $results[] = [
                SpyAclRoleTableMap::COL_CREATED_AT => $rule[SpyAclRoleTableMap::COL_CREATED_AT],
                SpyAclRoleTableMap::COL_NAME => $rule[SpyAclRoleTableMap::COL_NAME],
                self::ACTION => $this->createTableActions($rule),
            ];
        }

        return $results;
    }

    /**
     * @param array $rule
     *
     * @return string
     */
    protected function createTableActions(array $rule)
    {
        $deleteButton = '';
        $editButton = sprintf(
            '<a class="btn btn-xs btn-white" href="' . self::UPDATE_ROLE_URL . '">
                  Edit
             </a>
            ',
            $rule[SpyAclRoleTableMap::COL_ID_ACL_ROLE]
        );

        if ($rule[SpyAclRoleTableMap::COL_NAME] !== AclConfig::ROOT_ROLE) {
            $deleteButton = sprintf(
                '<a class="btn btn-xs btn-white" href="' . self::DELETE_ROLE_URL . '">
                  Delete
                 </a>
                ',
                $rule[SpyAclRoleTableMap::COL_ID_ACL_ROLE]
            );
        }

        return $editButton . $deleteButton;
    }

}
