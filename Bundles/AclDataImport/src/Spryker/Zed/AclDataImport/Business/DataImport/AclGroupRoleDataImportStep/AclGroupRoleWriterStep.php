<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclDataImport\Business\DataImport\AclGroupRoleDataImportStep;

use Orm\Zed\Acl\Persistence\SpyAclGroupsHasRolesQuery;
use Spryker\Zed\AclDataImport\Business\DataSet\AclGroupRoleDataImportInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AclGroupRoleWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $aclGroupRoleEntity = SpyAclGroupsHasRolesQuery::create()
            ->filterByFkAclGroup($dataSet[AclGroupRoleDataImportInterface::FK_ACL_GROUP])
            ->filterByFkAclRole($dataSet[AclGroupRoleDataImportInterface::FK_ACL_ROLE])
            ->findOneOrCreate();

        $aclGroupRoleEntity->fromArray($dataSet->getArrayCopy());

        $aclGroupRoleEntity->save();
    }
}
