<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclDataImport\Business\DataImport\AclGroupDataImportStep;

use Orm\Zed\Acl\Persistence\SpyAclGroupQuery;
use Spryker\Zed\AclDataImport\Business\DataSet\AclGroupDataImportInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AclGroupWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $aclGroupEntity = SpyAclGroupQuery::create()
            ->filterByName($dataSet[AclGroupDataImportInterface::ACL_GROUP_NAME])
            ->findOneOrCreate();

        $aclGroupEntity->fromArray($dataSet->getArrayCopy());

        $aclGroupEntity->save();
    }
}
