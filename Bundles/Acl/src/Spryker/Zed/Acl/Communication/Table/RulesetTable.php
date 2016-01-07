<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication\Table;

use Spryker\Zed\Acl\Persistence\AclQueryContainer;
use Orm\Zed\Acl\Persistence\Map\SpyAclRuleTableMap;
use Spryker\Zed\Application\Business\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class RulesetTable extends AbstractTable
{

    const PARAM_ID_RULE = 'id-rule';
    const PARAM_ID_ROLE = 'id-role';
    const REMOVE_ACL_RULESET_URL = '/acl/ruleset/delete';
    const EMPTY_HEADER_NAME = 'empty';

    /**
     * @var AclQueryContainer
     */
    private $aclQueryContainer;

    /**
     * @var int
     */
    private $idRole;

    /**
     * @param AclQueryContainer $aclQueryContainer
     * @param int $idRole
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
        $button = $this->generateRemoveButton(
            Url::generate(self::REMOVE_ACL_RULESET_URL, [
                self::PARAM_ID_RULE => $ruleset[SpyAclRuleTableMap::COL_ID_ACL_RULE],
                self::PARAM_ID_ROLE => $ruleset[SpyAclRuleTableMap::COL_FK_ACL_ROLE],
            ]),
            'Remove'
        );

        return $button;
    }

}
