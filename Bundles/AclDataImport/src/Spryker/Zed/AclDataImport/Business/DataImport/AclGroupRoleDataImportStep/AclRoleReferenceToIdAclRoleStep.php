<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclDataImport\Business\DataImport\AclGroupRoleDataImportStep;

use Orm\Zed\Acl\Persistence\SpyAclRoleQuery;
use Spryker\Zed\AclDataImport\Business\DataSet\AclGroupRoleDataImportInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AclRoleReferenceToIdAclRoleStep implements DataImportStepInterface
{
    /**
     * @var array<int>
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
        $aclRoleReference = $dataSet[AclGroupRoleDataImportInterface::ACL_ROLE_REFERENCE];
        if (isset($this->idAclRoleCache[$aclRoleReference])) {
            $dataSet[AclGroupRoleDataImportInterface::FK_ACL_ROLE] = $this->idAclRoleCache[$aclRoleReference];

            return;
        }

        $aclRoleEntity = SpyAclRoleQuery::create()->findOneByReference($aclRoleReference);
        if (!$aclRoleEntity) {
            throw new EntityNotFoundException(
                sprintf('Could not find AclRole by reference: "%s"', $aclRoleReference),
            );
        }

        $idAclRole = $aclRoleEntity->getIdAclRole();
        $this->idAclRoleCache[$aclRoleReference] = $idAclRole;
        $dataSet[AclGroupRoleDataImportInterface::FK_ACL_ROLE] = $idAclRole;
    }
}
