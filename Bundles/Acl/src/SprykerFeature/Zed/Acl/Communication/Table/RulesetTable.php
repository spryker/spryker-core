<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Table;

use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;
use SprykerFeature\Zed\Acl\Persistence\Propel\Map\SpyAclRuleTableMap;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class RulesetTable extends AbstractTable
{
    const REMOVE_ACL_RULESET_URL = '/acl/ruleset/delete?id-rule=%d&id-role=%d';
    const EMPTY_HEADER_NAME = 'empty';

    /**
     * @var AclQueryContainer
     */
    private $aclQueryContainer;

    /**
     * @var integer
     */
    private $idRole;

    /**
     * @param AclQueryContainer $aclQueryContainer
     * @param integer $idRole
     */
    public function __construct(AclQueryContainer $aclQueryContainer, $idRole)
    {
        $this->aclQueryContainer = $aclQueryContainer;
        $this->idRole = $idRole;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return mixed
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyAclRuleTableMap::COL_BUNDLE => 'Bundle',
            SpyAclRuleTableMap::COL_CONTROLLER => 'Controller',
            SpyAclRuleTableMap::COL_ACTION => 'Action',
            SpyAclRuleTableMap::COL_TYPE => 'Permission',
            self::EMPTY_HEADER_NAME => '',
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

        $config->setUrl(sprintf('ruleset-table?id-role=%d', $this->idRole));

        return $config;
    }

    /**
     * @param TableConfiguration $config
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
                self::EMPTY_HEADER_NAME => $this->createTableActions($ruleset),
            ];
        }

        return $results;
    }

    /**
     * @param array $ruleset
     *
     * @return string
     */
    public function createTableActions(array $ruleset)
    {
        $actionButtons = sprintf(
            '<a class="btn btn-xs btn-white" href="' . self::REMOVE_ACL_RULESET_URL . '">
                  Remove
             </a>',
            $ruleset[SpyAclRuleTableMap::COL_ID_ACL_RULE],
            $ruleset[SpyAclRuleTableMap::COL_FK_ACL_ROLE]
        );

        return $actionButtons;
    }
}
