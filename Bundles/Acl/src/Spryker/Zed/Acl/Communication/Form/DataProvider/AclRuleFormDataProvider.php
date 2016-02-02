<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication\Form\DataProvider;

use Generated\Shared\Transfer\RuleTransfer;
use Orm\Zed\Acl\Persistence\Map\SpyAclRuleTableMap;
use Spryker\Zed\Acl\Business\AclFacade;
use Spryker\Zed\Acl\Communication\Form\RuleForm;

class AclRuleFormDataProvider
{

    /**
     * @var AclFacade
     */
    protected $aclFacade;

    /**
     * @param AclFacade $aclFacade
     */
    public function __construct(AclFacade $aclFacade)
    {
        $this->aclFacade = $aclFacade;
    }

    /**
     * @param int $idAclRole
     *
     * @return array
     */
    public function getData($idAclRole)
    {
        $ruleTransfer = new RuleTransfer();
        $ruleTransfer->setFkAclRole($idAclRole);

        return $ruleTransfer->toArray();
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            RuleForm::OPTION_TYPE => $this->getPermissionSelectChoices(),
        ];
    }

    /**
     * @return array
     */
    protected function getPermissionSelectChoices()
    {
        return array_combine(
            SpyAclRuleTableMap::getValueSet(SpyAclRuleTableMap::COL_TYPE),
            SpyAclRuleTableMap::getValueSet(SpyAclRuleTableMap::COL_TYPE)
        );
    }

}
