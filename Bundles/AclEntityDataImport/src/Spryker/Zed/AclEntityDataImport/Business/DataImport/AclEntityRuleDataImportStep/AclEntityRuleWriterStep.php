<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntityRuleDataImportStep;

use Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery;
use Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntityRuleDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AclEntityRuleWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $aclEntityRuleEntity = $this->createAclEntityRuleQuery()
            ->filterByFkAclRole($dataSet[AclEntityRuleDataSetInterface::FK_ACL_ROLE])
            ->filterByEntity($dataSet[AclEntityRuleDataSetInterface::ENTITY])
            ->filterByScope($dataSet[AclEntityRuleDataSetInterface::SCOPE])
            ->findOneOrCreate();

        $aclEntityRuleEntity->fromArray($dataSet->getArrayCopy());
        $aclEntityRuleEntity->save();
    }

    /**
     * @return \Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery
     */
    protected function createAclEntityRuleQuery(): SpyAclEntityRuleQuery
    {
        return SpyAclEntityRuleQuery::create();
    }
}
