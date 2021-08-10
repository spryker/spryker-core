<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntityRuleDataImportStep;

use Orm\Zed\Acl\Persistence\Map\SpyAclRoleTableMap;
use Orm\Zed\Acl\Persistence\SpyAclRoleQuery;
use Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntityRuleDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AclRoleReferenceToAclRoleIdStep implements DataImportStepInterface
{
    /**
     * @var array int>
     */
    protected $idAclRoleCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $aclRoleReference = $dataSet[AclEntityRuleDataSetInterface::ACL_ROLE_REFERENCE];

        if (!isset($this->idAclRoleCache[$aclRoleReference])) {
            $aclRoleQuery = SpyAclRoleQuery::create();
            /** @var int|null $idAclRole */
            $idAclRole = $aclRoleQuery
                ->select(SpyAclRoleTableMap::COL_ID_ACL_ROLE)
                ->findOneByReference($aclRoleReference);

            if (!$idAclRole) {
                throw new EntityNotFoundException(sprintf('Could not find AclRole by reference: "%s"', $aclRoleReference));
            }

            $this->idAclRoleCache[$aclRoleReference] = $idAclRole;
        }

        $dataSet[AclEntityRuleDataSetInterface::FK_ACL_ROLE] = $this->idAclRoleCache[$aclRoleReference];
    }
}
