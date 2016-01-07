<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication\Table;

use Spryker\Zed\Acl\Persistence\AclQueryContainer;
use Orm\Zed\Acl\Persistence\Map\SpyAclRoleTableMap;
use Spryker\Zed\Application\Business\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Acl\AclConfig;

class RoleTable extends AbstractTable
{

    const ACTION = 'Action';
    const PARAM_ID_ROLE = 'id-role';
    const UPDATE_ROLE_URL = '/acl/role/update';
    const DELETE_ROLE_URL = '/acl/role/delete';

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
                self::ACTION => implode(' ', $this->createTableActions($rule)),
            ];
        }

        return $results;
    }

    /**
     * @param array $rule
     *
     * @return array
     */
    protected function createTableActions(array $rule)
    {
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            Url::generate(self::UPDATE_ROLE_URL, [self::PARAM_ID_ROLE => $rule[SpyAclRoleTableMap::COL_ID_ACL_ROLE]]),
            'Edit'
        );

        if ($rule[SpyAclRoleTableMap::COL_NAME] !== AclConfig::ROOT_ROLE) {
            $buttons[] = $this->generateRemoveButton(
                Url::generate(self::DELETE_ROLE_URL, [self::PARAM_ID_ROLE => $rule[SpyAclRoleTableMap::COL_ID_ACL_ROLE]]),
                'Delete'
            );
        }

        return $buttons;
    }

}
