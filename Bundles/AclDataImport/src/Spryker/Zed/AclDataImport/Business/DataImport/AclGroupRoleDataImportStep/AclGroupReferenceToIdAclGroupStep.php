<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclDataImport\Business\DataImport\AclGroupRoleDataImportStep;

use Orm\Zed\Acl\Persistence\SpyAclGroupQuery;
use Spryker\Zed\AclDataImport\Business\DataSet\AclGroupRoleDataImportInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AclGroupReferenceToIdAclGroupStep implements DataImportStepInterface
{
    /**
     * @var array<int>
     */
    protected $idAclGroupCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $aclGroupReference = $dataSet[AclGroupRoleDataImportInterface::ACL_GROUP_REFERENCE];
        if (isset($this->idAclGroupCache[$aclGroupReference])) {
            $dataSet[AclGroupRoleDataImportInterface::FK_ACL_GROUP] = $this->idAclGroupCache[$aclGroupReference];

            return;
        }
        $aclGroupEntity = SpyAclGroupQuery::create()->findOneByReference($aclGroupReference);
        if (!$aclGroupEntity) {
            throw new EntityNotFoundException(
                sprintf('Could not find AclGroup by reference: %s', $aclGroupReference)
            );
        }

        $idAclGroup = $aclGroupEntity->getIdAclGroup();
        $this->idAclGroupCache[$aclGroupReference] = $idAclGroup;
        $dataSet[AclGroupRoleDataImportInterface::FK_ACL_GROUP] = $idAclGroup;
    }
}
