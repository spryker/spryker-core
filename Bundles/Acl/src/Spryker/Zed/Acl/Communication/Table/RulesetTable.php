<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Table;

use Orm\Zed\Acl\Persistence\Map\SpyAclRuleTableMap;
use Spryker\Zed\Acl\Persistence\AclQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class RulesetTable extends AbstractTable
{
    public const PARAM_ID_RULE = 'id-rule';
    public const PARAM_ID_ROLE = 'id-role';
    public const REMOVE_ACL_RULESET_URL = '/acl/ruleset/delete';
    public const ACTIONS = 'actions';

    /**
     * @var \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface
     */
    protected $aclQueryContainer;

    /**
     * @var int
     */
    protected $idRole;

    /**
     * @param \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface $aclQueryContainer
     * @param int $idRole
     */
    public function __construct(AclQueryContainerInterface $aclQueryContainer, $idRole)
    {
        $this->aclQueryContainer = $aclQueryContainer;
        $this->idRole = $idRole;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyAclRuleTableMap::COL_BUNDLE => 'Bundle',
            SpyAclRuleTableMap::COL_CONTROLLER => 'Controller',
            SpyAclRuleTableMap::COL_ACTION => 'Action',
            SpyAclRuleTableMap::COL_TYPE => 'Permission',
            self::ACTIONS => 'Actions',
        ]);

        $config->setSortable([
            SpyAclRuleTableMap::COL_BUNDLE,
            SpyAclRuleTableMap::COL_CONTROLLER,
            SpyAclRuleTableMap::COL_ACTION,
            SpyAclRuleTableMap::COL_TYPE,
        ]);

        $config->setSearchable([
            SpyAclRuleTableMap::COL_BUNDLE,
            SpyAclRuleTableMap::COL_CONTROLLER,
            SpyAclRuleTableMap::COL_ACTION,
        ]);

        $config->addRawColumn(self::ACTIONS);

        $config->setUrl(sprintf('ruleset-table?id-role=%d', $this->idRole));

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $rulesetQuery = $this->aclQueryContainer->queryRuleByRoleId($this->idRole);
        $queryResults = $this->runQuery($rulesetQuery, $config);

        $results = [];
        foreach ($queryResults as $ruleset) {
            $results[] = [
                SpyAclRuleTableMap::COL_BUNDLE => $ruleset[SpyAclRuleTableMap::COL_BUNDLE],
                SpyAclRuleTableMap::COL_CONTROLLER => $ruleset[SpyAclRuleTableMap::COL_CONTROLLER],
                SpyAclRuleTableMap::COL_ACTION => $ruleset[SpyAclRuleTableMap::COL_ACTION],
                SpyAclRuleTableMap::COL_TYPE => $ruleset[SpyAclRuleTableMap::COL_TYPE],
                self::ACTIONS => implode(' ', $this->createTableActions($ruleset)),
            ];
        }

        return $results;
    }

    /**
     * @param array $ruleset
     *
     * @return array
     */
    public function createTableActions(array $ruleset)
    {
        $buttons = [];
        $buttons[] = $this->generateRemoveButton(self::REMOVE_ACL_RULESET_URL, 'Delete', [
            self::PARAM_ID_RULE => $ruleset[SpyAclRuleTableMap::COL_ID_ACL_RULE],
            self::PARAM_ID_ROLE => $ruleset[SpyAclRuleTableMap::COL_FK_ACL_ROLE],
        ]);

        return $buttons;
    }
}
