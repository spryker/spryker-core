<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentConnectorDataImportStep;

use Propel\Runtime\ActiveQuery\PropelQuery;
use Spryker\Service\AclEntity\AclEntityServiceInterface;
use Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntitySegmentConnectorDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AclEntitySegmentConnectorWriterStep implements DataImportStepInterface
{
   /**
    * @var \Spryker\Service\AclEntity\AclEntityServiceInterface
    */
    protected $aclEntityService;

    /**
     * @param \Spryker\Service\AclEntity\AclEntityServiceInterface $aclEntityService
     */
    public function __construct(AclEntityServiceInterface $aclEntityService)
    {
        $this->aclEntityService = $aclEntityService;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataEntity = $dataSet[AclEntitySegmentConnectorDataSetInterface::DATA_ENTITY];
        $connectorClassName = $this->aclEntityService->generateSegmentConnectorClassName($dataEntity);
        $connectorQuery = PropelQuery::from($connectorClassName);
        $referenceColumnName = $this->aclEntityService->generateSegmentConnectorReferenceColumnName(
            PropelQuery::from($dataEntity)->getTableMap()->getName()
        );
        $entity = $connectorQuery
            ->filterBy(
                $connectorQuery->getTableMap()->getColumn($referenceColumnName)->getPhpName(),
                $dataSet[AclEntitySegmentConnectorDataSetInterface::FK_TARGET_ENTITY]
            )
            ->filterByFkAclEntitySegment($dataSet[AclEntitySegmentConnectorDataSetInterface::FK_ACL_ENTITY_SEGMENT])
            ->findOneOrCreate();

        $entity->fromArray($dataSet);
        $targetEntitySetter = [$entity, $this->aclEntityService->generateSegmentConnectorReferenceSetter($dataEntity)];
        if (is_callable($targetEntitySetter)) {
            call_user_func($targetEntitySetter, $dataSet[AclEntitySegmentConnectorDataSetInterface::FK_TARGET_ENTITY]);
        }

        $entity->save();
    }
}
