<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclDataImport\Business\DataImport\AclRoleDataImportStep;

use Orm\Zed\Acl\Persistence\Base\SpyAclRoleQuery;
use Spryker\Zed\AclDataImport\Business\DataSet\AclRoleDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AclRoleWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $aclRoleEntity = SpyAclRoleQuery::create()
            ->filterByName($dataSet[AclRoleDataSetInterface::ACL_ROLE_NAME])
            ->findOneOrCreate();

        $aclRoleEntity->fromArray($dataSet->getArrayCopy());

        $aclRoleEntity->save();
    }
}
