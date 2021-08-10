<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentDataImportStep;

use Orm\Zed\AclEntity\Persistence\Base\SpyAclEntitySegmentQuery;
use Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntitySegmentDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AclEntitySegmentWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $entity = SpyAclEntitySegmentQuery::create()
            ->filterByReference($dataSet[AclEntitySegmentDataSetInterface::REFERENCE])
            ->findOneOrCreate();

        $entity->fromArray($dataSet->getArrayCopy());

        $entity->save();
    }
}
